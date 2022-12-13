<?php

namespace BestSnipp\Eden\Components\Fields;

use Carbon\Carbon;

class Time extends DateTime
{

    protected $phpFormat = 'h:i A';

    protected $jsFormat = 'h:i K';

    protected $isDatePicker = false;
    protected $isTimePicker = true;

    protected function onMount()
    {
        $this->meta = array_merge($this->meta, [
            'type' => 'time'
        ]);
    }
}
