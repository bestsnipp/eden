<?php

namespace Dgharami\Eden\Components\Fields;

class Password extends Field
{

    protected function onMount()
    {
        $this->meta = array_merge($this->meta, [
            'type' => 'password'
        ]);
    }

    public function view()
    {
        return view('eden::fields.input.text');
    }

}
