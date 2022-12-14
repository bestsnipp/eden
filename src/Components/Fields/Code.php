<?php

namespace BestSnipp\Eden\Components\Fields;

class Code extends Textarea
{
    public $visibilityOnIndex = false;

    protected function onMount()
    {
        $this->meta = array_merge($this->meta, [
            'rows' => 5,
            'cols' => 30
        ]);
    }

    public function view()
    {
        return view('eden::fields.input.code');
    }

    public function viewForRead()
    {
        parent::viewForRead();

        return view('eden::fields.view.code');
    }
}
