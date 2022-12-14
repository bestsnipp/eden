<?php

namespace BestSnipp\Eden\Components\Fields;

class Email extends Field
{
    protected function onMount()
    {
        $this->meta = array_merge($this->meta, [
            'type' => 'email'
        ]);
    }

    public function view()
    {
        return view('eden::fields.input.text');
    }

    public function viewForIndex()
    {
        parent::viewForIndex();

        return view('eden::fields.row.email');
    }

    public function viewForRead()
    {
        parent::viewForRead();

        return view('eden::fields.view.email');
    }

}
