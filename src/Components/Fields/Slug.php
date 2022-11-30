<?php

namespace Dgharami\Eden\Components\Fields;

use Dgharami\Eden\Components\Form;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Slug extends Field
{

    protected $generateFrom = '';

    protected $separator = '-';

    protected function onMount()
    {
        $this->resolveCallback = [$this, 'generateSlug'];
    }

    public function generateFrom($target = '')
    {
        $this->generateFrom = $target;
        return $this;
    }

    public function dependsOn($targets, $callback = null)
    {
        parent::dependsOn($targets, $callback);
        $this->generateFrom($this->targets[0] ?? '');
        return $this;
    }

    public function separator($separator = '-')
    {
        $this->separator = appCall($separator);
        return $this;
    }

    public function generateSlug($value, $field, $fields, Form $form)
    {
        if (isset($fields[$this->generateFrom]) && $form->isCreate() && empty($this->value)) {
            $field->value = strtolower(Str::slug($fields[$this->generateFrom]->getValue(), $this->separator));
        }
        return $field;
    }

    public function view()
    {
        return view('eden::fields.input.text');
    }

}
