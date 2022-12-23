<?php

namespace BestSnipp\Eden\Components\Metrics;

use BestSnipp\Eden\Traits\Makeable;
use Illuminate\Database\Eloquent\Builder as ModelBuilder;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SplitMetric extends MetricValue
{
    use Makeable;

    protected $values = [];

    protected $labels = [];

    protected $valuesCallback = null;

    protected $labelsCallback = null;

    protected $chartType = 'pie';

    protected $decimalPoint = 2;

    protected $chart = [
        "series" => [],
        "labels" => [],
        "chart" => [
            "type" => "pie",
            "height" => "auto",
            "stacked" => false,
            "sparkline" => [
                "enabled" => true
            ]
        ],
        "responsive" => [
            [
                "breakpoint" => 480,
                "options" => [
                    "chart" => [
                        "height" => "auto"
                    ]
                ]
            ]
        ]
    ];

    /**
     * @param \Closure|array $values
     * @return $this
     */
    public function result($values)
    {
        $values = appCall($values);

        $this->values = (is_null($values) && !is_array($values)) ? $this->values : array_values($values);
        $this->labels = (is_null($values) && !is_array($values)) ? $this->labels : array_keys($values);

        return $this;
    }

    /**
     * @param \Closure|array $currentValues
     * @return $this
     */
    public function values($currentValues)
    {
        $currentValues = appCall($currentValues);

        $this->values = (is_null($currentValues) && !is_array($currentValues)) ? [] : $currentValues;

        return $this;
    }

    /**
     * @param \Closure|array $labels
     * @return $this
     */
    public function labels($labels)
    {
        $labels = appCall($labels);

        $this->labels = (is_null($labels) && !is_array($labels)) ? [] : $labels;

        return $this;
    }

    /**
     * @param \Closure $callback
     * @return $this
     */
    public function transform($callback)
    {
        if (is_callable($callback)) {
            $this->valuesCallback = $callback;
        }

        return $this;
    }

    public function decimalPoint($point = 2)
    {
        $this->decimalPoint = $point;

        return $this;
    }

    /**
     * @param \Closure $callback
     * @return $this
     */
    public function transformLabels($callback)
    {
        if (is_callable($callback)) {
            $this->labelsCallback = $callback;
        }

        return $this;
    }

    /**
     * @param array $options
     * @return $this
     */
    public function setChartOptions($options)
    {
        $this->chart = array_merge($this->chart, $options);
        return $this;
    }

    /**
     * @return array
     */
    public function getChartOptions()
    {
        $formattedValues = is_null($this->valuesCallback) ? $this->values : appCall($this->valuesCallback, [
            'values' => $this->values,
            'labels' => $this->labels
        ]);
        $formattedLabels = is_null($this->labelsCallback) ? $this->labels : appCall($this->labelsCallback, [
            'labels' => $this->labels,
            'values' => $this->values
        ]);

        $chartWithData = array_merge($this->chart, [
            'series' => $formattedValues,
            'labels' => $formattedLabels
        ]);
        return $chartWithData;
    }

    /**
     * @param string $chartType
     * @return void
     */
    private function setChartType($chartType)
    {
        $this->chart['chart']['type'] = $chartType;

        switch (strtolower($chartType)) {
            case 'pie':
            case 'donut':
            case 'radar':
            case 'polarArea':
            case 'radialBar':
                break;
            default:
        }
    }

    /**
     * @return $this
     */
    public function asPieChart()
    {
        $this->setChartType('pie');
        return $this;
    }

    /**
     * @return $this
     */
    public function asDonutChart()
    {
        $this->setChartType('donut');
        return $this;
    }

    /**
     * @return $this
     */
    public function asRadarChart()
    {
        $this->setChartType('radar');
        return $this;
    }

    /**
     * @return $this
     */
    public function asPolarAreaChart()
    {
        $this->setChartType('polarArea');
        return $this;
    }

    /**
     * @return $this
     */
    public function asRadialBarChart()
    {
        $this->setChartType('radialBar');
        return $this;
    }

    /**
     * Return a progress result showing the growth of a count aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param string|null $groupBy
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function count($model, $column = null, $groupBy = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'count', $column, $groupBy, $dateColumn);
    }

    /**
     * Return a progress result showing the growth of a sum aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param string|null $groupBy
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function sum($model, $column = null, $groupBy = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'sum', $column, $groupBy, $dateColumn);
    }

    /**
     * Return a progress result showing the growth of a avg aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param string|null $groupBy
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function average($model, $column = null, $groupBy = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'avg', $column, $groupBy, $dateColumn);
    }

    /**
     * Return a progress result showing the growth of a min value.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param string|null $groupBy
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function min($model, $column = null, $groupBy = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'min', $column, $groupBy, $dateColumn);
    }

    /**
     * Return a progress result showing the growth of a max value.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @param string|null $groupBy
     * @return $this
     */
    public function max($model, $column = null, $groupBy = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'max', $column, $groupBy, $dateColumn);
    }

    /**
     * Return a progress result showing the segments of a aggregate.
     *
     * @param \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param \Illuminate\Database\Query\Expression|string|null  $column
     * @param string|null $groupBy
     * @param \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    protected function aggregate($model, $function, $column, $groupBy = null, $dateColumn = null)
    {
        $query = ($model instanceof ModelBuilder || $model instanceof QueryBuilder) ? $model : (new $model)->newQuery();

        $column = $column ?? $query->getModel()->getQualifiedKeyName();
        $dateColumn = $dateColumn ?? $query->getModel()->getQualifiedCreatedAtColumn();
        $currentDateRange = $this->getCurrentDateRange($this->activeFilter, $this->getTimezone());

        $query->tap(function ($query) {
            return $this->applyFiltersInQuery($query);
        });
        //$query->dd();

        $results = with(clone $query)->select(DB::raw("{$function}({$column}) as aggregate"), $groupBy)
                ->whereBetween($dateColumn, $this->formatQueryDateBetween($currentDateRange, $this->getTimezone()))
                ->groupBy($groupBy)
                ->get();
        $this->result($results->pluck('aggregate', $groupBy)->toArray());

        return $this;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        return view('eden::metrics.split')->with([
            'chart' => $this->getChartOptions(),
            'decimalPoint' => $this->decimalPoint
        ]);
    }

}
