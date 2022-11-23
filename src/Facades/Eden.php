<?php

namespace Dgharami\Eden\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static menu()
 * @method static accountMenu()
 * @method static registerComponents($directory, $namespace = null, $basePath = null)
 */
class Eden extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'eden';
    }

}
