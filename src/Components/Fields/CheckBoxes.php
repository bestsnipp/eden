<?php

namespace BestSnipp\Eden\Components\Fields;

class CheckBoxes extends Field
{

    protected $value = [];

    protected $meta = [
        'type' => 'checkbox',
        'class' => 'focus:ring-0 h-5 w-5 text-indigo-600 border-gray-300 rounded dark:bg-slate-600 dark:border-slate-700 focus-within:border-indigo-700 dark:text-slate-300 dark:checked:bg-slate-800 dark:hover:bg-slate-500 dark:focus:ring-slate-500'
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
        parent::viewForIndex();

        return view('eden::fields.row.checkbox');
    }

    public function viewForRead()
    {
        parent::viewForRead();

        return view('eden::fields.view.checkbox')
            ->with('hideUnchecked', $this->hideUnchecked);
    }

}
