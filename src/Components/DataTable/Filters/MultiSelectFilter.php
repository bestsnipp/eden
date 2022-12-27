<?php

namespace BestSnipp\Eden\Components\DataTable\Filters;

class MultiSelectFilter extends Filter
{
    public $initialValue = [];

    public $value = [];

    protected $options = [];

    protected $isKeyValue = false;

    public function options($options = [])
    {
        $this->options = $options;

        return $this;
    }

    public function resolveOptions()
    {
        return [];
    }

    public function keyValueOptions($options = [])
    {
        $this->options = $options;
        $this->isKeyValue = true;

        return $this;
    }

    public function getAppliedValue()
    {
        if ($this->isKeyValue) {
            return collect($this->options)->filter(function ($item) {
                return in_array($item['value'], $this->value);
            })->pluck('label')->all() ?? $this->value;
        } else {
            return collect($this->options)->filter(function ($item, $key) {
                return in_array($key, $this->value);
            })->all() ?? $this->value;
        }
    }

    public function apply($query, $value)
    {
        return $query->whereIn($this->key, $value);
    }

    public function view()
    {
        $options = (empty($this->resolveOptions())) ? $this->options : $this->resolveOptions();

        return view('eden::datatable.filters.multi-select')
            ->with('options', $options)
            ->with('isKeyValue', $this->isKeyValue);
    }
}
