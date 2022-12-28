<?php

namespace BestSnipp\Eden\Components\Fields;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Livewire\TemporaryUploadedFile;


class Boolean extends Field
{
    protected $value = '';

    protected $trueValue = true;

    protected $falseValue = false;

    public function onMount()
    {
        $this->meta = array_merge($this->meta, [
            'class' => 'sr-only peer',
            'type' => 'checkbox',
        ]);
    }

    /**
     * Set True Value to set in database rather than true
     *
     * @param boolean|bool $value
     * @return $this
     */
    public function trueValue($value = true)
    {
        $this->trueValue = $value;
        return $this;
    }

    /**
     * Set False Value to set in database rather than false
     *
     * @param boolean|bool $value
     * @return $this
     */
    public function falseValue($value = false)
    {
        $this->falseValue = $value;
        return $this;
    }

    public function process()
    {
        $status = boolval($this->value);
        return $status ? $this->trueValue : $this->falseValue;
    }

    public function view()
    {
        return view('eden::fields.input.boolean');
    }

    public function viewForIndex()
    {
        $this->value = $this->value == $this->trueValue;
        parent::viewForIndex();

        return view('eden::fields.row.boolean');
    }

    public function viewForRead()
    {
        $this->value = $this->value == $this->trueValue;
        parent::viewForRead();

        return view('eden::fields.view.boolean');
    }
}
