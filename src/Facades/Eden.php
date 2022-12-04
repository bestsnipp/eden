<?php

namespace Dgharami\Eden\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static mainMenu($callback)
 * @method static getMainMenu()
 * @method static accountMenu($callback)
 * @method static getAccountMenu()
 * @method static registerActions($actions = [])
 * @method static actions()
 * @method static registerFilters($filters = [])
 * @method static filters()
 * @method static footer($callback = null)
 * @method static getFooter()
 * @method static logo($callback = null)
 * @method static getLogo()
 * @method static getCurrentRoute()
 * @method static registerComponents($directory, $namespace = null, $basePath = null)
 */
class Eden extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'eden';
    }
}
