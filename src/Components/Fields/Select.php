<?php

namespace BestSnipp\Eden\Components\Fields;

class Select extends Field
{
    protected $filterable = true;

    public function multiple()
    {
        $this->meta = array_merge($this->meta, [
            'multiple' => 'multiple',
        ]);

        return $this;
    }

    public function disableSearchFilter()
    {
        $this->filterable = false;

        return $this;
    }

    public function view()
    {
        return view('eden::fields.input.select')
                ->with('searchFilterEnabled', $this->filterable);
    }

    public function viewForIndex()
    {
        parent::viewForIndex();

        return view('eden::fields.row.select');
    }

    public function viewForRead()
    {
        parent::viewForRead();

        return view('eden::fields.view.select');
    }
}
