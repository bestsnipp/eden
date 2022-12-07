<?php

namespace Dgharami\Eden\Components\Fields;

class RadioBoxes extends Field
{

    protected $value = '';

    protected $meta = [
        'type' => 'radio',
        'class' => 'focus:ring-0 h-5 w-5 text-indigo-600 border-gray-300'
    ];

    public function view()
    {
        return view('eden::fields.input.radio-checkbox');
    }

    public function viewForIndex()
    {
        return view('eden::fields.row.radio');
    }

    public function viewForRead()
    {
        return view('eden::fields.view.radio');
    }

}
