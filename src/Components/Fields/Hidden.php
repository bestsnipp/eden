<?php

namespace BestSnipp\Eden\Components\Fields;

class Hidden extends Field
{
    public $visibilityOnIndex = false;
    protected function onMount()
    {
        $this->meta = array_merge($this->meta, [
            'type' => 'hidden'
        ]);
    }

    public function view()
    {
        return view('eden::fields.input.hidden');
    }

}
