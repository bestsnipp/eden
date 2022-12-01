<?php

namespace Dgharami\Eden\Traits;

trait HasEdenResource
{
    public $edenResource = '';

    protected function getResourceData(callable $callback, $default = '')
    {
        $edenResource = app($this->edenResource);
        return $callback($edenResource);
    }

}
