<?php
namespace BestSnipp\Eden\Components\DataTable\Column;

use BestSnipp\Eden\Components\Fields\Field;

class ActionField extends Field
{

    protected $sortable = false;

    protected $searchable = false;

    public $textAlign = 'right';

    protected $actions = [];

    public function withActions($actions = [])
    {
        $this->actions = $actions;
        return $this;
    }

    public function viewForIndex()
    {
        return view('eden::datatable.column.actions')
            ->with('actions', $this->actions)
            ->with('record', $this->record)
            ->with('iconSize', 'scale-50')
            ->with('buttonStyle', 'bg-white hover:bg-slate-10 transition w-auto text-slate-500 rounded-md py-1 px-1 inline-block text-sm');
    }

    public function viewForRead()
    {
        return view('eden::datatable.column.actions')
            ->with('actions', $this->actions)
            ->with('record', $this->record)
            ->with('iconSize', 'scale-75')
            ->with('buttonStyle', 'inline-flex items-center gap-2 px-3 py-2 bg-slate-800 border border-transparent rounded-md text-white hover:bg-slate-700 active:bg-slate-900 tracking-wide focus:outline-none focus:border-slate-900 focus:ring focus:ring-slate-300 disabled:opacity-25 transition text-gray-700');
    }

}
