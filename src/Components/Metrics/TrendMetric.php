<?php

namespace BestSnipp\Eden\Components\Metrics;

use BestSnipp\Eden\Traits\Makeable;
use Illuminate\Database\Eloquent\Builder as ModelBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;

class TrendMetric extends MetricValue
{
    use Makeable;

    /**
     * Trend units.
     */
    const BY_YEARS = 'year';

    const BY_MONTHS = 'month';

    const BY_WEEKS = 'week';

    const BY_DAYS = 'day';

    const BY_HOURS = 'hour';

    const BY_MINUTES = 'minute';

    protected $value = 0;

    protected $showLatest = false;

    protected $current = [];

    protected $previous = [];

    protected $labels = [];

    protected $compare = false;

    protected $valuesCallback = null;

    protected $previousValuesCallback = null;

    protected $valueCallback = null;

    protected $labelsCallback = null;

    protected $displayCallback = null;

    protected $chartType = 'area';

    protected $chart = [
        'series' => [],
        'labels' => [],
        'chart' => [
            'type' => 'area',
            'height' => '120px',
            'stacked' => false,
            'sparkline' => [
                'enabled' => true,
            ],
        ],
    ];

    /**
     * @param  \Closure|array  $currentValues
     * @return $this
     */
    public function current($currentValues)
    {
        $currentValues = appCall($currentValues);

        $this->current = (is_null($currentValues) && ! is_array($currentValues)) ? [] : $currentValues;

        return $this;
    }

    /**
     * @param  \Closure|array  $previousValues
     * @return $this
     */
    public function previous($previousValues)
    {
        $previousValues = appCall($previousValues);

        $this->previous = (is_null($previousValues) && ! is_array($previousValues)) ? [] : $previousValues;
        if (is_array($this->previous) && count($this->previous) > 0) {
            $this->compare = true;
        }

        return $this;
    }

    /**
     * @param  \Closure|array  $labels
     * @return $this
     */
    public function labels($labelValues)
    {
        $labelValues = appCall($labelValues);

        $this->labels = (is_null($labelValues) && ! is_array($labelValues)) ? [] : $labelValues;

        return $this;
    }

    /**
     * @param  bool  $should
     * @return $this
     */
    public function compare($should = true)
    {
        $this->compare = appCall($should);

        return $this;
    }

    /**
     * @param  bool  $should
     * @return $this
     */
    public function showLatest(bool $should = true)
    {
        $this->showLatest = appCall($should);

        return $this;
    }

    /**
     * @param  \Closure  $callback
     * @return $this
     */
    public function displayUsing($callback)
    {
        if (is_callable($callback)) {
            $this->displayCallback = $callback;
        }

        return $this;
    }

    /**
     * @param  \Closure  $callback
     * @return $this
     */
    public function transform($callback)
    {
        if (is_callable($callback)) {
            $this->valuesCallback = $callback;
        }

        return $this;
    }

    /**
     * @param  \Closure  $callback
     * @return $this
     */
    public function transformPrevious($callback)
    {
        if (is_callable($callback)) {
            $this->previousValuesCallback = $callback;
        }

        return $this;
    }

    /**
     * @param  \Closure  $callback
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
     * @param  array  $options
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
        $formattedValues = is_null($this->valuesCallback) ? $this->current : appCall($this->valuesCallback, [
            'current' => $this->current,
            'previous' => $this->previous,
            'labels' => $this->labels,
        ]);
        $formattedPreviousValues = is_null($this->previousValuesCallback) ? $this->previous : appCall($this->previousValuesCallback, [
            'current' => $this->current,
            'previous' => $this->previous,
            'labels' => $this->labels,
        ]);
        $formattedLabels = is_null($this->labelsCallback) ? (empty($this->labels) ? $this->current : $this->labels) : appCall($this->labelsCallback, [
            'current' => $this->current,
            'previous' => $this->previous,
            'labels' => $this->labels,
        ]);
        $this->value = last($formattedValues) ?? 0;

        $series = [];
        if ($this->compare) {
            $series[] = ['name' => 'Previous', 'data' => $formattedPreviousValues];
        }
        $series[] = ['name' => 'Current', 'data' => $formattedValues];

        $chartWithData = array_merge($this->chart, [
            'series' => $series,
            'labels' => $formattedLabels,
        ]);

        if ($this->showLatest) {
            $chartWithData['chart']['height'] = '75px';
        }

        return $chartWithData;
    }

    /**
     * @param  string  $chartType
     * @return void
     */
    private function setChartType($chartType)
    {
        $this->chart['chart']['type'] = $chartType;

        switch (strtolower($chartType)) {
            case 'line':
                $this->chart['stroke']['curve'] = 'smooth';
                break;
            case 'column':
                $this->chart['chart']['type'] = 'bar';
                $this->chart['plotOptions']['bar'] = [
                    'horizontal' => false,
                    'endingShape' => 'rounded',
                    'borderRadius' => 4,
                ];
                break;
            case 'bar':
                $this->chart['plotOptions']['bar'] = [
                    'horizontal' => true,
                    'endingShape' => 'rounded',
                    'borderRadius' => 4,
                ];
                break;
            case 'heatmap':
                $this->chart['plotOptions']['heatmap'] = [
                    'radius' => 4,
                ];
                break;
            default:
        }
    }

    /**
     * @return $this
     */
    public function asLineChart()
    {
        $this->setChartType('line');

        return $this;
    }

    /**
     * @return $this
     */
    public function asAreaChart()
    {
        $this->setChartType('area');

        return $this;
    }

    /**
     * @return $this
     */
    public function asColumnChart()
    {
        $this->setChartType('column');

        return $this;
    }

    /**
     * @return $this
     */
    public function asBarChart()
    {
        $this->setChartType('bar');

        return $this;
    }

    /**
     * @return $this
     */
    private function asBubbleChart() // NOT WORKING
    {
        $this->setChartType('bubble');

        return $this;
    }

    /**
     * @return $this
     */
    public function asScatterChart()
    {
        $this->setChartType('scatter');

        return $this;
    }

    /**
     * @return $this
     */
    public function asHeatmapChart()
    {
        $this->setChartType('heatmap');

        return $this;
    }

    /**
     * @return $this
     */
    private function asTreemapChart() // NOT WORKING - WORKS WITH ONLY CURRENT DATA SET
    {
        $this->setChartType('treemap');

        return $this;
    }

    /**
     * Return a result showing the growth of a count aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function countByYears($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'count', $column, $dateColumn, self::BY_YEARS);
    }

    /**
     * Return a result showing the growth of a count aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function countByMonths($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'count', $column, $dateColumn, self::BY_MONTHS);
    }

    /**
     * Return a result showing the growth of a count aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function countByWeeks($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'count', $column, $dateColumn, self::BY_WEEKS);
    }

    /**
     * Return a result showing the growth of a count aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function countByDays($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'count', $column, $dateColumn, self::BY_DAYS);
    }

    /**
     * Return a result showing the growth of a count aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function countByHours($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'count', $column, $dateColumn, self::BY_HOURS);
    }

    /**
     * Return a result showing the growth of a count aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function countByMinutes($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'count', $column, $dateColumn, self::BY_MINUTES);
    }

    /**
     * Return a result showing the growth of a average aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function averageByYears($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'avg', $column, $dateColumn, self::BY_YEARS);
    }

    /**
     * Return a result showing the growth of a average aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function averageByMonths($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'avg', $column, $dateColumn, self::BY_MONTHS);
    }

    /**
     * Return a result showing the growth of a average aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function averageByWeeks($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'avg', $column, $dateColumn, self::BY_WEEKS);
    }

    /**
     * Return a result showing the growth of a average aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function averageByDays($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'avg', $column, $dateColumn, self::BY_DAYS);
    }

    /**
     * Return a result showing the growth of a average aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function averageByHours($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'avg', $column, $dateColumn, self::BY_HOURS);
    }

    /**
     * Return a result showing the growth of a average aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function averageByMinutes($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'avg', $column, $dateColumn, self::BY_MINUTES);
    }

    /**
     * Return a result showing the growth of a sum aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function sumByYears($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'sum', $column, $dateColumn, self::BY_YEARS);
    }

    /**
     * Return a result showing the growth of a sum aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function sumByMonths($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'sum', $column, $dateColumn, self::BY_MONTHS);
    }

    /**
     * Return a result showing the growth of a sum aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function sumByWeeks($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'sum', $column, $dateColumn, self::BY_WEEKS);
    }

    /**
     * Return a result showing the growth of a sum aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function sumByDays($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'sum', $column, $dateColumn, self::BY_DAYS);
    }

    /**
     * Return a result showing the growth of a sum aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function sumByHours($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'sum', $column, $dateColumn, self::BY_HOURS);
    }

    /**
     * Return a result showing the growth of a sum aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function sumByMinutes($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'sum', $column, $dateColumn, self::BY_MINUTES);
    }

    /**
     * Return a result showing the growth of a min aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function minByYears($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'min', $column, $dateColumn, self::BY_YEARS);
    }

    /**
     * Return a result showing the growth of a min aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function minByMonths($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'min', $column, $dateColumn, self::BY_MONTHS);
    }

    /**
     * Return a result showing the growth of a min aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function minByWeeks($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'min', $column, $dateColumn, self::BY_WEEKS);
    }

    /**
     * Return a result showing the growth of a min aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function minByDays($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'min', $column, $dateColumn, self::BY_DAYS);
    }

    /**
     * Return a result showing the growth of a min aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function minByHours($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'min', $column, $dateColumn, self::BY_HOURS);
    }

    /**
     * Return a result showing the growth of a min aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function minByMinutes($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'min', $column, $dateColumn, self::BY_MINUTES);
    }

    /**
     * Return a result showing the growth of a max aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function maxByYears($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'max', $column, $dateColumn, self::BY_YEARS);
    }

    /**
     * Return a result showing the growth of a max aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function maxByMonths($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'max', $column, $dateColumn, self::BY_MONTHS);
    }

    /**
     * Return a result showing the growth of a max aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function maxByWeeks($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'max', $column, $dateColumn, self::BY_WEEKS);
    }

    /**
     * Return a result showing the growth of a max aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function maxByDays($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'max', $column, $dateColumn, self::BY_DAYS);
    }

    /**
     * Return a result showing the growth of a max aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function maxByHours($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'max', $column, $dateColumn, self::BY_HOURS);
    }

    /**
     * Return a result showing the growth of a max aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @return $this
     */
    public function maxByMinutes($model, $column = null, $dateColumn = null)
    {
        return $this->aggregate($model, 'max', $column, $dateColumn, self::BY_MINUTES);
    }

    /**
     * Return a result showing the segments of a aggregate.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|class-string<\Illuminate\Database\Eloquent\Model>  $model
     * @param  \Illuminate\Database\Query\Expression|string|null  $column
     * @param  \Illuminate\Database\Query\Expression|string|null  $dateColumn
     * @param  \Illuminate\Database\Query\Expression|string|null  $labelColumn
     * @return $this
     */
    protected function aggregate($model, $function, $column, $dateColumn, $unit)
    {
        $query = ($model instanceof ModelBuilder || $model instanceof QueryBuilder) ? $model : (new $model)->newQuery();

        $column = $column ?? $query->getModel()->getQualifiedKeyName();
        $dateColumn = $dateColumn ?? $query->getModel()->getQualifiedCreatedAtColumn();
        $currentDateRange = $this->getCurrentDateRange($this->activeFilter, $this->getTimezone());
        $previousDateRange = $this->getPreviousDateRange($this->activeFilter, $this->getTimezone());
        $trendExpression = $this->getTrendExpression($dateColumn, $unit, $query);

        $query->tap(function ($query) {
            return $this->applyFiltersInQuery($query);
        });

        // Make DB Query
        $currentTrends = (strtoupper($this->activeFilter) === 'ALL') ? with(clone $query) : with(clone $query)
            ->whereBetween($dateColumn, $this->formatQueryDateBetween($currentDateRange, $this->getTimezone()));

        $currentTrends = $currentTrends->select(DB::raw("{$function}({$column}) as trend_value"))
            ->addSelect(DB::raw("{$trendExpression} as trend_index"))
            ->groupBy(DB::raw('trend_index'))->get()->pluck('trend_value', 'trend_index');

        $previousTrends = (strtoupper($this->activeFilter) === 'ALL') ? with(clone $query) : with(clone $query)
            ->whereBetween($dateColumn, $this->formatQueryDateBetween($previousDateRange, $this->getTimezone()));

        $previousTrends = $previousTrends->select(DB::raw("{$function}({$column}) as trend_value"))
            ->addSelect(DB::raw("{$trendExpression} as trend_index"))
            ->groupBy(DB::raw('trend_index'))->get()->pluck('trend_value', 'trend_index');

        // Set Current Value
        $this->current = array_values($currentTrends->transform(function ($item) {
            return $item;
        })->toArray());

        // Set Previous Value
        $this->previous = array_values($previousTrends->transform(function ($item) {
            return $item;
        })->toArray());

        // Set Labels
        $this->labels = array_values($currentTrends->transform(function ($item, $trendIndex) {
            return $trendIndex;
        })->toArray());

        return $this;
    }

    /**
     * @param  string  $by
     * @return string|void
     */
    protected function getTrendExpression($column, $by, $query)
    {
        $offset = $this->getTimezoneOffset($this->getTimezone());

        if ($offset > 0) {
            $interval = '+ INTERVAL '.$offset.' HOUR';
        } elseif ($offset === 0) {
            $interval = '';
        } else {
            $interval = '- INTERVAL '.($offset * -1).' HOUR';
        }

        $queryWrap = $query->getQuery()->getGrammar()->wrap($column);

        switch (strtolower($by)) {
            case 'year':
                return "date_format({$queryWrap} {$interval}, '%Y')";
            case 'month':
                return "date_format({$queryWrap} {$interval}, '%Y-%m')";
            case 'week':
                return "date_format({$queryWrap} {$interval}, '%x-%v')";
            case 'day':
                return "date_format({$queryWrap} {$interval}, '%Y-%m-%d')";
            case 'hour':
                return "date_format({$queryWrap} {$interval}, '%Y-%m-%d %H:00')";
            case 'minute':
                return "date_format({$queryWrap} {$interval}, '%Y-%m-%d %H:%i:00')";
        }
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        $valueLabel = is_null($this->valueCallback) ? $this->value : appCall($this->valueCallback, [
            'value' => $this->value,
            'current' => $this->current,
            'previous' => $this->previous,
            'labels' => $this->labels,
        ]);

        $currentSeries = $this->current;
        $previousSeries = $this->previous;
        $value = $this->value;
        $chartOptions = $this->getChartOptions();

        if (!is_null($this->displayCallback)) {
            $currentDateRange = $this->getCurrentDateRange($this->activeFilter, $this->getTimezone());
            $previousDateRange = $this->getPreviousDateRange($this->activeFilter, $this->getTimezone());

            $displayData = appCall($this->displayCallback, [
                'currentSeries' => $currentSeries,
                'previousSeries' => $previousSeries,
                'chartOptions' => $chartOptions,
                'valueLabel' => $valueLabel,
                'value' => $value,
                'currentDateRange' => $currentDateRange,
                'previousDateRange' => $previousDateRange,
            ]);
            if (isset($displayData['currentSeries'])) {
                $currentSeries = $displayData['currentSeries'];
            }
            if (isset($displayData['previousSeries'])) {
                $previousSeries = $displayData['previousSeries'];
            }
            if (isset($displayData['valueLabel'])) {
                $valueLabel = $displayData['valueLabel'];
            }
            if (isset($displayData['value'])) {
                $value = $displayData['value'];
            }
            if (isset($displayData['chartOptions'])) {
                $chartOptions = $displayData['chartOptions'];
            }
        }

        return view('eden::metrics.trend')->with([
            'currentSeries' => $currentSeries,
            'previousSeries' => $previousSeries,
            'compare' => $this->compare,
            'chart' => $chartOptions,
            'value' => $value,
            'valueLabel' => $valueLabel,
            'showLatest' => $this->showLatest,
        ]);
    }
}
