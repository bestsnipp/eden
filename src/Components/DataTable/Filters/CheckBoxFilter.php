<?php

namespace BestSnipp\Eden\Components\DataTable\Filters;

class CheckBoxFilter extends MultiSelectFilter
{
    public function apply($query, $value)
    {
        return $query->whereIn($this->key, $this->value);
    }

    public function view()
    {
        $options = (empty($this->resolveOptions())) ? $this->options : $this->resolveOptions();

        return view('eden::datatable.filters.checkbox')->with('options', $options);
    }
}
