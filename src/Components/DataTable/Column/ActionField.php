<?php
namespace Dgharami\Eden\Components\DataTable\Column;

use Dgharami\Eden\Components\Fields\Field;

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
            ->with('buttonStyle', 'bg-white hover:bg-slate-10 transition w-auto text-slate-500 rounded-md py-1 px-1 inline-block text-sm');
    }

}
