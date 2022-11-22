<?php

namespace Dgharami\Eden\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static whoami()
 */
class Eden extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'eden';
    }

}
