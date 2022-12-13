<?php

namespace BestSnipp\Eden\Components\DataTable\Filters;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;

class TimeFilter extends Filter
{
    protected $format = 'H:i';

    /**
     * Date Format
     *
     * @param $format
     * @return $this
     */
    public function format($format = 'H:i')
    {
        $this->format = $format;
        return $this;
    }

    public function apply($query, $value)
    {
        return $query->whereTime($this->key, '>=', $this->value);
    }

    public function view()
    {
        return view('eden::datatable.filters.time')->with('format', $this->format);
    }
}
