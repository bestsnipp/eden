<?php

namespace Dgharami\Eden\Components\Fields;

class Color extends Field
{

    protected $value = '#666666';

    public function view()
    {
        return view('eden::fields.input.color');
    }

}
