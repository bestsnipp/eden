<?php

namespace Dgharami\Eden\Components\Fields;

class Number extends Field
{

    protected function onMount()
    {
        $this->meta = array_merge($this->meta, [
            'type' => 'tel'
        ]);
    }

    public function min($min)
    {
        $this->meta = array_merge($this->meta, [
            'min' => $min
        ]);
        return $this;
    }

    public function max($max)
    {
        $this->meta = array_merge($this->meta, [
            'max' => $max
        ]);
        return $this;
    }

    public function step($step = 1)
    {
        $this->meta = array_merge($this->meta, [
            'step' => $step
        ]);
        return $this;
    }

    public function view()
    {
        return view('eden::fields.input.text');
    }

}
