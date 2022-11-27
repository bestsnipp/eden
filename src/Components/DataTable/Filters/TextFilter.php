<?php

namespace Dgharami\Eden\Components\DataTable\Filters;

use Illuminate\Database\Query\Builder;

class TextFilter extends Filter
{

    public function apply($query, $value)
    {
        return $query->where($this->key, 'LIKE', "%$value%");
    }

}
