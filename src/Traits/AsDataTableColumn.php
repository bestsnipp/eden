<?php

namespace BestSnipp\Eden\Traits;

use Illuminate\Support\Facades\DB;

trait AsDataTableColumn
{
    protected $as = '';

    protected $sortable = true;

    protected $searchable = true;

    protected $order = '';

    public $textAlign = 'left';

    /**
     * Current Record that is being rendering
     *
     * @var mixed
     */
    protected $record = null;

    protected $displayCallback = null;

    final public function sortable($should = true)
    {
        $this->sortable = appCall($should);

        return $this;
    }

    final public function textAlign($textAlign = 'left')
    {
        $this->textAlign = in_array($textAlign, ['left', 'right', 'center']) ? $textAlign : $this->textAlign;

        return $this;
    }

    final public function searchable($should = true)
    {
        $this->searchable = appCall($should);

        return $this;
    }

    final public function isSearchable()
    {
        return $this->searchable;
    }

    final public function orderBy($order = '')
    {
        $this->order = $order;

        return $this;
    }

    final public function getOrderBy()
    {
        return $this->order;
    }

    final public function isSortable()
    {
        return $this->sortable;
    }

    public function apply($query)
    {
//        if ($this->key != $this->as) {
//            return $query->addSelect(DB::raw("{$this->as} as {$this->key}"));
//        }
        return $query;
    }

    public function record($record)
    {
        $this->record = $record;

        return $this;
    }

    public function withRecord($record)
    {
        return $this->record($record);
    }

    /**
     * @return null
     */
    protected function getDisplayCallback()
    {
        return $this->displayCallback;
    }

    /**
     * @param  mixed  $displayCallback
     */
    public function displayUsing($displayCallback)
    {
        $this->displayCallback = $displayCallback;

        return $this;
    }

    public function viewForIndexHeader()
    {
        return view('eden::fields.header.text');
    }

    public function viewForIndex()
    {
        $this->value = is_null($this->displayCallback) ? $this->value : appCall($this->displayCallback, [
            'value' => $this->value,
            'field' => $this,
        ]);

        return view('eden::fields.row.text');
    }
}
