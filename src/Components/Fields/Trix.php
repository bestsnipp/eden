<?php

namespace BestSnipp\Eden\Components\Fields;

class Trix extends Textarea
{
    protected function onMount()
    {
        $this->meta = array_merge($this->meta, [
            'rows' => 5,
            'cols' => 30,
        ]);
    }

    public function view()
    {
        return view('eden::fields.input.trix');
    }
}
