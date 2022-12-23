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

    protected function apply($query, $value) {
        return $query->where($this->key, 'LIKE', "%$value%");
    }

    public function view()
    {
        $options = (empty($this->resolveOptions())) ? $this->options : $this->resolveOptions();
        if (count($options) > 0) {
            $options = array_merge(['' => 'Select'], $options);
        }

        return view('eden::datatable.filters.select')
                ->with('options', $options)
                ->with('isKeyValue', $this->isKeyValue);
    }
}
