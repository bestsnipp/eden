<?php

namespace BestSnipp\Eden\Components\DataTable\Filters;

class DateFilter extends Filter
{
    protected $format = 'Y-m-d';

    /**
     * Date Format
     *
     * @param $format
     * @return $this
     */
    public function format($format = 'Y-m-d')
    {
        $this->format = $format;

        return $this;
    }

    public function apply($query, $value)
    {
        return $query->whereDate($this->key, $this->value);
    }

    public function view()
    {
        return view('eden::datatable.filters.date')->with('format', $this->format);
    }
}
