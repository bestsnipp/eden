<?php

namespace BestSnipp\Eden\Components\Fields;

use Carbon\Carbon;

class Date extends DateTime
{
    protected $phpFormat = 'Y-m-d';

    protected $jsFormat = 'Y-m-d';

    protected $isDatePicker = true;

    protected $isTimePicker = false;

    protected function onMount()
    {
        $this->meta = array_merge($this->meta, [
            'type' => 'date',
        ]);
    }

    /**
     * @param  mixed  $value
     */
    public function default($value)
    {
        $this->value = ($value instanceof Carbon) ? $value->toDateString() : $value;

        return $this;
    }
}
