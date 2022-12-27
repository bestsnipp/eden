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
            ->with('iconSize', 'scale-95')
            ->with('buttonStyle', config('eden.button_style_table'));
    }

    public function viewForRead()
    {
        return view('eden::datatable.column.actions')
            ->with('actions', $this->actions)
            ->with('record', $this->record)
            ->with('iconSize', 'scale-75')
            ->with('buttonStyle', config('eden.button_style'));
    }
}
