<?php

namespace BestSnipp\Eden\Components\Metrics;

use BestSnipp\Eden\Traits\Makeable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as ModelBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\Log;
use ReflectionClass;

class StatisticMetric extends MetricValue
{
    use Makeable;

    protected $previous = 0;

    protected $value = 0;

    protected $valueCallback = null;

    protected $previousCallback = null;

    protected $prefix = '';

    protected $suffix = '';

    protected $showIcon = true;

    protected $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="currentColor" viewBox="0 0 512 512">
        <path d="M64 400C64 408.8 71.16 416 80 416H480C497.7 416 512 430.3 512 448C512 465.7 497.7 480 480 480H80C35.82 480 0 444.2 0 400V64C0 46.33 14.33 32 32 32C49.67 32 64 46.33 64 64V400zM342.6 278.6C330.1 291.1 309.9 291.1 297.4 278.6L240 221.3L150.6 310.6C138.1 323.1 117.9 323.1 105.4 310.6C92.88 298.1 92.88 277.9 105.4 265.4L217.4 153.4C229.9 140.9 250.1 140.9 262.6 153.4L320 210.7L425.4 105.4C437.9 92.88 458.1 92.88 470.6 105.4C483.1 117.9 483.1 138.1 470.6 150.6L342.6 278.6z"/>
    </svg>';

    protected $iconColor = 'bg-primary-200 text-primary-500';

    private $iconColors = [
        'primary' => 'bg-primary-200 text-primary-500',
        'red' => 'bg-red-200 text-red-500',
        'orange' => 'bg-orange-200 text-orange-500',
        'amber' => 'bg-amber-200 text-amber-500',
        'yellow' => 'bg-yellow-200 text-yellow-500',
        'lime' => 'bg-lime-200 text-lime-500',
        'green' => 'bg-green-200 text-green-500',
        'emerald' => 'bg-emerald-200 text-emerald-500',
        'teal' => 'bg-teal-200 text-teal-500',
        'cyan' => 'bg-cyan-200 text-cyan-500',
        'sky' => 'bg-sky-200 text-sky-500',
        'blue' => 'bg-blue-200 text-blue-500',
        'indigo' => 'bg-indigo-200 text-indigo-500',
        'violet' => 'bg-violet-200 text-violet-500',
        'fuchsia' => 'bg-fuchsia-200 text-fuchsia-500',
        'pink' => 'bg-pink-200 text-pink-500',
        'rose' => 'bg-rose-200 text-rose-500'
    ];

    protected function __construct($filter = null)
    {
        parent::__construct($filter);

        if (is_null($this->iconColor)) {
            $this->iconColor = $this->iconColors[ array_rand($this->iconColors) ];
        }
    }

    /**
     * @param string $icon
     * @return $this
     */
    public function withIcon($icon = null)
    {
        $icon = appCall($icon);

        $this->icon = is_null($icon) ? $this->icon : $icon;
        $this->showIcon = !is_null($this->icon);
        return $this;
    }

    /**
     * @param string $iconColor
     * @return $this
     */
    public function iconColor($iconColor = 'primary')
    {
        $this->iconColor = isset($this->iconColors[$iconColor]) ? $this->iconColors[$iconColor] : $iconColor;
        return $this;
    }

    /**
     * @param float|double|integer $value
     * @return $this
     */
    public function value($value)
    {
        $this->value = appCall($value);

        return $this;
    }

    /**
     * @param float|double|integer $previous
     * @return $this
     */
    public function previous($previous)
    {
        $previous = appCall($previous);

        $this->previous = is_null($previous) ? $this->previous : $previous;

        return $this;
    }

    /**
     * Format the Value
     *
     * @param \Closure $callback
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
     * Format the Previous Value
     *
     * @param \Closure $callback
     * @return $this
     */
    public function displayPrevious($callback)
    {
        if (is_callable($callback)) {
            $this->previousCallback = $callback;
        }

        return $this;
    }

    /**
     * @param float|double|integer $present
     * @param float|double|integer $previous
     * @return float|double|integer
     */
    protected function calculatePercentageChange($present, $previous)
    {
        $change = 0;
        try {
            $change = (($present - $previous) / ($previous ?: 100)) * 100;
        } catch (\Exception $exception) {
            if ($present != $previous) {
                $change = 100;
            }
        }

        return round($change, 2, PHP_ROUND_HALF_UP);
    }

    /**
     * Return a result showing the growth of a count aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function count($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'count', $column, $dateColumn);
    }

    /**
     * Return a result showing the growth of a sum aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function sum($model, $column, $dateColumn = null)
    {
        return $this->aggregate($model, 'sum', $column, $dateColumn);
    }

    /**
     * Return a result showing the growth of a avg aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function average($model, $column, $dateColumn = null)
    {
        return $this->aggregate($model, 'avg', $column, $dateColumn);
    }

    /**
     * Return a result showing the growth of a max value.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function max($model, $column, $dateColumn = null)
    {
        return $this->aggregate($model, 'max', $column, $dateColumn);
    }

    /**
     * Return a result showing the growth of a min value.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function min($model, $column, $dateColumn = null)
    {
        return $this->aggregate($model, 'min', $column, $dateColumn);
    }

    /**
     * Return a result showing the segments of a aggregate.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    protected function aggregate($model, $function, $column, $dateColumn)
    {
        $query = ($model instanceof ModelBuilder || $model instanceof QueryBuilder) ? $model : (new $model)->newQuery();

        $column = $column ?? $query->getModel()->getQualifiedKeyName();

        if ($this->activeFilter === 'ALL') {
            $this->value = round(with(clone $query)->{$function}($column), 0, PHP_ROUND_HALF_UP);
            return $this;
        }

        $dateColumn = $dateColumn ?? $query->getModel()->getQualifiedCreatedAtColumn();
        $currentDateRange = $this->getCurrentDateRange($this->activeFilter, $this->getTimezone());
        $previousDateRange = $this->getPreviousDateRange($this->activeFilter, $this->getTimezone());

        $query->tap(function ($query) {
            return $this->applyFiltersInQuery($query);
        });

        // Set Current Value
        $this->value = round(with(clone $query)
                ->whereBetween($dateColumn, $this->formatQueryDateBetween($currentDateRange, $this->getTimezone()))
                ->{$function}($column) ?? 0);

        // Set Previous Value
        $this->previous = round(with(clone $query)
                ->whereBetween($dateColumn, $this->formatQueryDateBetween($previousDateRange, $this->getTimezone()))
                ->{$function}($column) ?? 0);

        return $this;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        $valueLabel = is_null($this->valueCallback) ? $this->value : appCall($this->valueCallback, [
            'value' => $this->value,
            'previous' => $this->previous
        ]);
        $previousLabel = is_null($this->previousCallback) ? $this->previous : appCall($this->previousCallback, [
            'value' => $this->value,
            'previous' => $this->previous
        ]);

        return view('eden::metrics.statistic')->with([
            'valueLabel' => $valueLabel,
            'previousLabel' => $previousLabel,
            'value' => $this->value,
            'previous' => $this->previous,
            'icon' => $this->icon,
            'showIcon' => $this->showIcon,
            'iconColor' => $this->iconColor,
            'change' => $this->calculatePercentageChange($this->value, $this->previous)
        ]);
    }

}
