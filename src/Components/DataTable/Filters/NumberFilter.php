<?php

namespace BestSnipp\Eden\Components\DataTable\Filters;

use Illuminate\Database\Query\Builder;

class NumberFilter extends Filter
{
    public $initialValue = 0;

    public $value = 0;

    public function apply($query, $value)
    {
        return $query->where($this->key, $value);
    }

    public function view()
    {
        return view('eden::datatable.filters.number');
    }
}
