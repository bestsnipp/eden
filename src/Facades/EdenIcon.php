<?php

namespace BestSnipp\Eden\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static getIcon($icon)
 */
class EdenIcon extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'edenIconManager';
    }
}
