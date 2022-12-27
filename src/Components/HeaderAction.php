<?php

namespace BestSnipp\Eden\Components;

use BestSnipp\Eden\RenderProviders\HeaderActionRenderer;

/**
 * @method static make(array $params = [])
 */
abstract class HeaderAction extends EdenComponent
{
    public function defaultViewParams()
    {
        return [];
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        return view('eden::components.card');
    }

    /**
     * @param  string  $class
     * @param  array  $params
     * @return HeaderActionRenderer
     */
    protected static function renderer($class, $params)
    {
        return new HeaderActionRenderer($class, $params);
    }
}
