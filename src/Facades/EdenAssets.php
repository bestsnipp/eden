<?php

namespace Dgharami\Eden\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static styles()
 * @method static scripts()
 * @method static registerStyle(string $url, $key = null, $attributes = [])
 * @method static registerScripts(string $url, $key = null, $attributes = [])
 */
class EdenAssets extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'edenAssetsManager';
    }
}
