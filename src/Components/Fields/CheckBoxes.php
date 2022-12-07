<?php

namespace Dgharami\Eden\Components\Fields;

class CheckBoxes extends Field
{

    protected $value = [];

    protected $meta = [
        'type' => 'checkbox',
        'class' => 'focus:ring-0 h-5 w-5 text-indigo-600 border-gray-300 rounded'
    ];

    protected $hideUnchecked = false;

    public function hideUnchecked($should = true)
    {
        $this->hideUnchecked = appCall($should);
        return $this;
    }

    public function view()
    {
        return view('eden::fields.input.radio-checkbox');
    }

    public function viewForIndex()
    {
        return view('eden::fields.row.checkbox');
    }

    public function viewForRead()
    {
        return view('eden::fields.view.checkbox')
            ->with('hideUnchecked', $this->hideUnchecked);
    }

}
