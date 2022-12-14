<?php

namespace BestSnipp\Eden\Components\Fields;

class Color extends Field
{

    protected $value = '#666666';

    public function view()
    {
        return view('eden::fields.input.color');
    }

    public function viewForIndex()
    {
        parent::viewForIndex();

        return view('eden::fields.row.color');
    }

    public function viewForRead()
    {
        parent::viewForRead();

        return view('eden::fields.view.color');
    }

}
