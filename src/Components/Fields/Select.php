<?php

namespace Dgharami\Eden\Components\Fields;

class Select extends Field
{

    public function multiple()
    {
        $this->meta = array_merge($this->meta, [
            'multiple' => 'multiple'
        ]);
        return $this;
    }

    public function view()
    {
        return view('eden::fields.input.select');
    }

}
