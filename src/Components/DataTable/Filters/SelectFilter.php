<?php

namespace BestSnipp\Eden\Components\DataTable\Filters;

class SelectFilter extends Filter
{
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
            return collect($this->options)->first(function ($item) {
                return $item['value'] == $this->value;
            })['label'] ?? $this->value;
        } else {
            return (isset($this->options[$this->value])) ? $this->options[$this->value] : $this->value;
        }
    }

    protected function apply($query, $value)
    {
        return $query->where($this->key, 'LIKE', "%$value%");
    }

    public function view()
    {
        $options = (empty($this->resolveOptions())) ? $this->options : $this->resolveOptions();
        if (count($options) > 0) {
            $options = $this->isKeyValue
                ? array_merge([['value' => '', 'label' => 'Select']], $options)
                : array_merge(['' => 'Select'], $options);
        }

        if ($this->isKeyValue) {
            $options = collect($options)->filter(function ($value, $key) {
                return !empty($value);
            })->all();
        } else {
            $options = collect($options)->filter()->all();
        }

        return view('eden::datatable.filters.select')
                ->with('options', $options)
                ->with('isKeyValue', $this->isKeyValue);
    }
}
