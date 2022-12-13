<?php

namespace BestSnipp\Eden\Components\Fields;

use Carbon\Carbon;

class DateTime extends Field
{
    protected $phpFormat = 'd/m/Y h:i A';

    protected $jsFormat = 'd/m/Y h:i K';

    protected $isDatePicker = true;
    protected $isTimePicker = true;

    protected function onMount()
    {
        $this->meta = array_merge($this->meta, [
            'type' => 'datetime-local'
        ]);
    }

    public function phpFormat($value)
    {
        $this->phpFormat = $value;
        return $this;
    }

    public function jsFormat($value)
    {
        $this->jsFormat = $value;
        return $this;
    }

    /**
     * @param mixed $value
     */
    public function default($value)
    {
        $this->value = ($value instanceof Carbon) ? $value->toDateTimeLocalString() : $value;
        return $this;
    }

    public function viewForRead()
    {
        $this->value = ($this->value instanceof Carbon)
            ? $this->value->format($this->phpFormat)
            : Carbon::parse($this->value)->format($this->phpFormat);
        return view('eden::fields.view.text');
    }

    public function viewForIndex()
    {
        $this->value = ($this->value instanceof Carbon)
            ? $this->value->format($this->phpFormat)
            : Carbon::parse($this->value)->format($this->phpFormat);
        return parent::viewForIndex();
    }

    public function view()
    {
        return view('eden::fields.input.date-time')
            ->with([
                'isDatePicker' => $this->isDatePicker,
                'isTimePicker' => $this->isTimePicker,
                'format' => $this->jsFormat
            ]);
    }

}
