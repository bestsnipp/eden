<?php

namespace Dgharami\Eden\Components\Fields;

class Hidden extends Field
{

    protected function onMount()
    {
        $this->meta = array_merge($this->meta, [
            'type' => 'hidden',
            'class' => 'border border-slate-500'
        ]);
    }

    public function view()
    {
        return view('eden::fields.input.hidden');
    }

}
