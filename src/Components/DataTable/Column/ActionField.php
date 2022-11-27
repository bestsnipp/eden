<?php
namespace Dgharami\Eden\Components\DataTable\Column;

use Dgharami\Eden\Components\DataTable\Column;
use Dgharami\Eden\Components\Fields\Field;
use Dgharami\Eden\Traits\HasTitleKey;
use Illuminate\Support\Str;

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
            ->with('record', $this->record);
    }

}
