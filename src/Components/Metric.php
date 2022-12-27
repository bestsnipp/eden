<?php

namespace BestSnipp\Eden\Components;

use BestSnipp\Eden\Components\Metrics\MetricValue;
use BestSnipp\Eden\Components\Metrics\SplitMetric;
use BestSnipp\Eden\Components\Metrics\TrendMetric;
use BestSnipp\Eden\RenderProviders\CardRenderer;
use Carbon\CarbonImmutable;

/**
 * @method static make(array $params = [])
 */
abstract class Metric extends EdenComponent
{
    public $filter = '';

    public $blankCanvas = false;

    protected $styleCard = 'bg-white shadow-md rounded-md relative dark:bg-slate-700';

    /**
     * @return MetricValue
     */
    abstract public function calculate();

    /**
     * @return array
     */
    protected function filters()
    {
        return [];
    }

    /**
     * @param  string  $key
     * @return mixed
     */
    public function filterLabel($key = null)
    {
        return collect($this->filters())->first(function ($itemValue, $itemKey) use ($key) {
            return is_null($key) ? $itemKey == $this->filter : $itemKey == $key;
        });
    }

    /**
     * Provide the current date range to Calculate result
     *
     * @param  string|int  $key
     * @param  string  $timezone
     * @return array<CarbonImmutable>
     */
    public function currentDateRange($key, $timezone)
    {
        return [];
    }

    /**
     * Provide the previous date range to Calculate result
     *
     * @param  string|int  $key
     * @param  string  $timezone
     * @return array<CarbonImmutable>
     */
    public function previousDateRange($key, $timezone)
    {
        return [];
    }

    /**
     * @return MetricValue
     */
    final protected function prepareForRender()
    {
        $calculatedData = $this->calculate();
        if ($calculatedData instanceof TrendMetric || $calculatedData instanceof SplitMetric) {
            $this->dispatchBrowserEvent('alpine-refresh', $calculatedData->getChartOptions());
        }

        return $calculatedData;
    }

    public function defaultViewParams()
    {
        return [
            'blankCanvas' => $this->blankCanvas,
            'hasFilters' => count($this->filters()) > 0,
            'filters' => collect($this->filters())->toArray(),
            'styleCard' => $this->styleCard,
            'data' => $this->prepareForRender(),
        ];
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        return view('eden::components.card');
    }

    /**
     * @param  string  $class
     * @param  array  $params
     * @return CardRenderer
     */
    protected static function renderer($class, $params)
    {
        return new CardRenderer($class, $params);
    }
}
