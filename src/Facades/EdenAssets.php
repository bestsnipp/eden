<?php

namespace BestSnipp\Eden\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static styles()
 * @method static scripts()
 * @method static generateBrandColors()
 * @method static registerStyle(string $url, $key = null, $attributes = [])
 * @method static registerScript(string $url, $key = null, $attributes = [])
 */
class EdenAssets extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'edenAssetsManager';
    }
}
