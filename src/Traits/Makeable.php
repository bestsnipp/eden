<?php

namespace Dgharami\Eden\Traits;

trait Makeable
{

    /**
     * @param mixed ...$arguments
     * @return $this
     */
    public static function make(...$arguments)
    {
        return new static(...$arguments);
    }

}
