<?php

if (! function_exists('appCall')) {
    /**
     * Return the default value of the given value with dependency injection.
     *
     * @param  mixed  $value
     * @return mixed
     */
    function appCall($value, $args = [], $defaultMethods = null)
    {
        return is_callable($value) ? app()->call($value, $args, $defaultMethods) : $value;
    }
}

if (! function_exists('edenIcon')) {
    /**
     * Return the default value of the given value with dependency injection.
     *
     * @param  mixed $component
     * @param  array $params
     * @return mixed
     */
    function edenIcon($icon, $scale = null)
    {
        return \BestSnipp\Eden\Facades\EdenIcon::getIcon($icon, $scale);
    }
}
