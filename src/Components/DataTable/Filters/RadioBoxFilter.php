<?php

namespace BestSnipp\Eden\Components\DataTable\Filters;

class RadioBoxFilter extends SelectFilter
{
    public function apply($query, $value)
    {
        return $query->where($this->key, $this->value);
    }

    public function view()
    {
        $options = (empty($this->resolveOptions())) ? $this->options : $this->resolveOptions();

        return view('eden::datatable.filters.radio')->with('options', $options);
    }
}
