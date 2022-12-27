<?php

namespace BestSnipp\Eden\Components\Metrics;

use BestSnipp\Eden\Traits\Makeable;
use Illuminate\Database\Eloquent\Builder as ModelBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

class ProgressMetric extends MetricValue
{
    use Makeable;

    protected $target = 0;

    protected $progress = 0;

    protected $shouldAvoid = false;

    protected $valueCallback = null;

    protected $targetCallback = null;

    /**
     * @param  \Closure|float|float|int  $target
     * @return $this
     */
    public function target($target)
    {
        $this->target = appCall($target);

        return $this;
    }

    /**
     * @param  \Closure|float|float|int  $current
     * @return $this
     */
    public function progress($current)
    {
        $this->progress = appCall($current);

        return $this;
    }

    /**
     * @param  bool  $should
     * @return $this
     */
    public function avoid($should = true)
    {
        $this->shouldAvoid = appCall($should);

        return $this;
    }

    /**
     * @param  \Closure  $callback
     * @return $this
     */
    public function display($callback)
    {
        if (is_callable($callback)) {
            $this->valueCallback = $callback;
        }

        return $this;
    }

    /**
     * @param  \Closure  $callback
     * @return $this
     */
    public function displayTarget($callback)
    {
        if (is_callable($callback)) {
            $this->targetCallback = $callback;
        }

        return $this;
    }

    /**
     * @return float|int|float
     */
    private function calculatePercentage()
    {
        try {
            return ($this->progress / $this->target) * 100;
        } catch (\Exception $exception) {
            // Can Cause -> Division by zero
            if ($this->progress != $this->target) {
                return 100;
            }
        }

        return 0;
    }

    /**
     * Return a progress result showing the growth of a count aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @param  int|float|null  $target
     * @return $this
     */
    public function count($model, $column = null, $dateColumn = null, $target = null)
    {
        return $this->aggregate($model, 'count', $column, $dateColumn, $target);
    }

    /**
     * Return a progress result showing the growth of a sum aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @param  int|float|null  $target
     * @return $this
     */
    public function sum($model, $column, $dateColumn = null, $target = null)
    {
        return $this->aggregate($model, 'sum', $column, $dateColumn, $target);
    }

    /**
     * Return a progress result showing the growth of a avg aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @param  int|float|null  $target
     * @return $this
     */
    public function average($model, $column, $dateColumn = null, $target = null)
    {
        return $this->aggregate($model, 'avg', $column, $dateColumn, $target);
    }

    /**
     * Return a progress result showing the growth of a max value.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @param  int|float|null  $target
     * @return $this
     */
    public function max($model, $column, $dateColumn = null, $target = null)
    {
        return $this->aggregate($model, 'max', $column, $dateColumn, $target);
    }

    /**
     * Return a progress result showing the growth of a min value.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @param  int|float|null  $target
     * @return $this
     */
    public function min($model, $column, $dateColumn = null, $target = null)
    {
        return $this->aggregate($model, 'min', $column, $dateColumn, $target);
    }

    /**
     * Return a progress result showing the segments of a aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @param  int|float|null  $target
     * @return $this
     */
    protected function aggregate($model, $function, $column, $dateColumn, $target = null)
    {
        $query = ($model instanceof ModelBuilder || $model instanceof QueryBuilder) ? $model : (new $model)->newQuery();

        $column = $column ?? $query->getModel()->getQualifiedKeyName();

        if ($this->activeFilter === 'ALL') {
            $this->progress = round(with(clone $query)->{$function}($column) ?? 0);

            return $this;
        }

        $dateColumn = $dateColumn ?? $query->getModel()->getQualifiedCreatedAtColumn();
        $currentDateRange = $this->getCurrentDateRange($this->activeFilter, $this->getTimezone());

        $query->tap(function ($query) {
            return $this->applyFiltersInQuery($query);
        });

        // Set Current Progress Value
        $this->progress = round(with(clone $query)
                ->whereBetween($dateColumn, $this->formatQueryDateBetween($currentDateRange, $this->getTimezone()))
                ->{$function}($column) ?? 0);

        // Set Target Value
        $this->target = $target ?? $this->progress;

        return $this;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        $calculatedPercentage = $this->calculatePercentage();
        $valueLabel = is_null($this->valueCallback) ? number_format($calculatedPercentage, 2).'%' : appCall($this->valueCallback, [
            'percentage' => $calculatedPercentage,
            'value' => $this->progress,
            'target' => $this->target,
        ]);
        $targetLabel = is_null($this->targetCallback) ? $this->target : appCall($this->targetCallback, [
            'percentage' => $calculatedPercentage,
            'value' => $this->progress,
            'target' => $this->target,
        ]);

        return view('eden::metrics.progress')->with([
            'target' => $this->target,
            'valueLabel' => $valueLabel,
            'targetLabel' => $targetLabel,
            'progress' => $this->progress,
            'percentage' => $calculatedPercentage,
            'avoid' => $this->shouldAvoid,
        ]);
    }
}
