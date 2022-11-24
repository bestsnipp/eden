<?php

namespace Dgharami\Eden\Components\Fields;

use Illuminate\Support\Str;

class Textarea extends Field
{

    protected function onMount()
    {
        $this->meta = array_merge($this->meta, [
            'rows' => 2,
            'cols' => 30
        ]);
    }

    public function rows($row)
    {
        $this->meta = array_merge($this->meta, [
            'rows' => $row
        ]);
        return $this;
    }

    public function cols($cols)
    {
        $this->meta = array_merge($this->meta, [
            'cols' => $cols
        ]);
        return $this;
    }

    public function view()
    {
        return view('eden::fields.input.textarea');
    }

}
