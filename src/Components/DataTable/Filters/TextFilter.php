<?php

namespace BestSnipp\Eden\Components\DataTable\Filters;

class TextFilter extends Filter
{
    public function apply($query, $value)
    {
        return $query->where($this->key, 'LIKE', "%$value%");
    }
}
