<?php
namespace BestSnipp\Eden\Components\DataTable\Column;

use BestSnipp\Eden\Components\DataTable\Column;
use BestSnipp\Eden\Components\Fields\Field;
use BestSnipp\Eden\Traits\HasTitleKey;
use Illuminate\Support\Str;

class SelectorField extends Field
{
    protected $sortable = false;

    protected $searchable = false;

    public $textAlign = 'left';

    public ?string $title = 'Select';

    public function viewForIndexHeader()
    {
        return view('eden::datatable.column.header.selector');
    }

    public function viewForIndex()
    {
        return view('eden::datatable.column.selector')->with('record', $this->record);
    }

}
