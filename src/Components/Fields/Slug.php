<?php

namespace Dgharami\Eden\Components\Fields;

use Dgharami\Eden\Components\Form;
use Illuminate\Support\Str;

class Slug extends Field
{

    protected $target = null;

    protected $separator = '-';

    protected function onMount()
    {
        $this->resolveCallback = [$this, 'generateSlug'];
    }

    public function target($fieldKey)
    {
        $this->target = appCall($fieldKey);
        return $this;
    }

    public function separator($separator = '-')
    {
        $this->separator = appCall($separator);
        return $this;
    }

    public function generateSlug($value, $field, $fields, Form $form)
    {
        if (isset($fields[$this->target]) && $form->isCreate() && empty($this->value)) {
            $this->value = strtolower(Str::slug($fields[$this->target]->getValue(), $this->separator));
        }
        return $field;
    }

    public function view()
    {
        return view('eden::fields.input.text');
    }

}
