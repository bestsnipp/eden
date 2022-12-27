<?php

namespace BestSnipp\Eden\Components\Metrics;

use BestSnipp\Eden\Traits\Makeable;

class ListMetric extends MetricValue
{
    use Makeable;

    protected $items = [];

    protected $itemsCallback = null;

    protected $singleLine = false;

    protected $maxItems = -1;

    /**
     * Add Multiple Items
     *
     * @param  \Closure|array  $items
     * @return $this
     */
    public function items($items = [])
    {
        $this->items = array_merge($this->items, appCall($items));

        return $this;
    }

    /**
     * Add Single Items
     *
     * @param  \Closure|array  $items
     * @return $this
     */
    public function addItem($title, $description = '', $actions = [], $icon = '')
    {
        $this->items[] = [
            'icon' => $icon,
            'title' => $title,
            'description' => $description,
            'actions' => is_array($actions) ? $actions : collect($actions)->all(),
        ];

        return $this;
    }

    /**
     * Maximum item that will show in front end
     *
     * @param  \Closure|int  $maxItems
     * @return $this
     */
    public function maxItems($maxItems = -1)
    {
        $this->maxItems = appCall($maxItems);

        return $this;
    }

    /**
     * @param  \Closure  $callback
     * @return $this
     */
    public function display($callback)
    {
        if (is_callable($callback)) {
            $this->itemsCallback = $callback;
        }

        return $this;
    }

    /**
     * @param  bool|\Closure  $callback
     * @return $this
     */
    public function singleLine($isSingle = true)
    {
        $this->singleLine = appCall($isSingle);

        return $this;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        $formattedItems = collect(is_null($this->itemsCallback) ? $this->items : appCall($this->itemsCallback, [
            'items' => $this->items,
        ]))->transform(function ($item) {
            return (array) $item;
        });

        return view('eden::metrics.list')->with([
            'items' => ($this->maxItems == -1) ? $formattedItems->all() : $formattedItems->take($this->maxItems),
            'singleLine' => $this->singleLine,
        ]);
    }
}
