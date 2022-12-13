<?php

namespace BestSnipp\Eden\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static register($route)
 * @method static routes()
 * @method static has($slug)
 * @method static get($slug)
 */
class EdenRoute extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'edenRouteManager';
    }
}
