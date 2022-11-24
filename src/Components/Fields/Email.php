<?php

namespace Dgharami\Eden\Components\Fields;

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

}
