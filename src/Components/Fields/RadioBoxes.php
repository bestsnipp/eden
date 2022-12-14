<?php

namespace BestSnipp\Eden\Components\Fields;

class RadioBoxes extends Field
{

    protected $value = '';

    protected $meta = [
        'type' => 'radio',
        'class' => 'focus:ring-0 h-5 w-5 text-indigo-600 border-slate-300 dark:bg-slate-600 dark:border-slate-700 focus-within:border-indigo-700 dark:text-slate-300 dark:checked:bg-slate-800 dark:hover:bg-slate-500 dark:focus:ring-slate-500'
    ];

    public function view()
    {
        return view('eden::fields.input.radio-checkbox');
    }

    public function viewForIndex()
    {
        parent::viewForIndex();

        return view('eden::fields.row.radio');
    }

    public function viewForRead()
    {
        parent::viewForRead();

        return view('eden::fields.view.radio');
    }

}
