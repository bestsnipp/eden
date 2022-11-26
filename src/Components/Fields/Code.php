<?php

namespace Dgharami\Eden\Components\Fields;

class Code extends Textarea
{

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

}
