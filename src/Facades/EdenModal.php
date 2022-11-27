<?php

namespace Dgharami\Eden\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static register($modalComponent)
 * @method static modals()
 * @method static has($name)
 * @method static get($name)
 */
class EdenModal extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'modalManager';
    }

}
