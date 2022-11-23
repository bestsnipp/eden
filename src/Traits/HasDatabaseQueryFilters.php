<?php

namespace Dgharami\Eden\Traits;

use Dgharami\Eden\Components\DataTable\Filters\Filter;
use Illuminate\Support\Collection;

trait HasDatabaseQueryFilters
{
    /**
     * Filters for the metric.
     *
     * @var \Illuminate\Support\Collection|array
     */
    protected $queryFilters = [];

    /**
     * Set filters for current metric.
     *
     * @param  \Illuminate\Support\Collection|array  $filters
     * @return $this
     */
    public function applyQueryFilters($filters)
    {
        $this->queryFilters = ($filters instanceof Collection) ? $filters->toArray() : $filters;

        return $this;
    }

    /**
     * Set filter for current metric.
     *
     * @param Filter $filters
     * @return $this
     */
    public function applyQueryFilter($filter)
    {
        if (!is_null($filter) && is_subclass_of($filter, Filter::class)) {
            $this->queryFilters[] = $filter;
        }

        return $this;
    }

    /**
     * Apply filter query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected function applyFiltersInQuery($query)
    {
        collect($this->queryFilters)->each(function ($filter) use ($query) {
            if (is_callable([$filter, 'apply'])) {
                return $filter->apply($query, $filter->value);
            }
            return $query;
        });

        return $query;
    }
}
