<?php

namespace BestSnipp\Eden\Components\Fields;

use Carbon\Carbon;
use DateTimeZone;

class Timezone extends Select
{

    protected function onMount()
    {
        $this->options = collect(DateTimeZone::listIdentifiers(DateTimeZone::ALL))
            ->mapWithKeys(function ($timezone) {
            return [$timezone => $timezone];
        })->all();
        $this->default('UTC');
    }

    public function view()
    {
        return view('eden::fields.input.select');
    }
}
