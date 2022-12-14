<?php

namespace BestSnipp\Eden\Facades;

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
 * @method static registerHeaderActions($callback = null)
 * @method static headerActions()
 * @method static getCurrentRoute()
 * @method static getCurrentUrl()
 * @method static getPreviousUrl()
 * @method static isActionAuthorized($ability, $modelOrClass)
 * @method static registerComponents($directory, $namespace = null, $basePath = null)
 */
class Eden extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'eden';
    }
}
