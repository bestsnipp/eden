<?php

namespace Dgharami\Eden\Components\Fields;

class Color extends Field
{

    protected $value = '#666666';

    public function view()
    {
        return view('eden::fields.input.color');
    }

    public function viewForIndex()
    {
        return view('eden::fields.row.color');
    }

    public function viewForRead()
    {
        return view('eden::fields.view.color');
    }

}
