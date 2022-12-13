<?php

namespace BestSnipp\Eden\Components\DataTable\Filters;

use Illuminate\Database\Query\Builder;

class MultiSelectFilter extends Filter
{
    public $initialValue = [];

    public $value = [];

    protected $options = [];

    public function options($options = [])
    {
        $this->options = $options;
        return $this;
    }

    public function resolveOptions()
    {
        return [];
    }

    public function apply($query, $value) {
        return $query->whereIn($this->key, $value);
    }

    public function view()
    {
        $options = (empty($this->resolveOptions())) ? $this->options : $this->resolveOptions();
        return view('eden::datatable.filters.multi-select')->with('options', $options);
    }
}
