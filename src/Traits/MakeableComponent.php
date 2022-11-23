<?php

namespace Dgharami\Eden\Traits;

use Dgharami\Eden\RenderProviders\RenderProvider;
use Livewire\Component;

trait MakeableComponent
{

    /**
     * @param string $class
     * @param array $params
     * @return RenderProvider
     */
    protected static function renderer($class, $params)
    {
        return new RenderProvider($class, $params);
    }

    /**
     * @param $params
     * @return RenderProvider
     */
    public static function make($params = [])
    {
        return self::renderer(get_called_class(), $params);
    }

    public function mountMakeableComponent()
    {
        if (method_exists($this, 'onMount')) {
            appCall([$this, 'onMount']);
        }
    }
}
