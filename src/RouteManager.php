<?php

namespace BestSnipp\Eden;

use BestSnipp\Eden\Components\EdenPage;

class RouteManager
{
    private $routes = [];

    /**
     * @param  mixed  $route
     * @return void
     */
    public function register($route)
    {
        $routeInstance = app($route);
        if ($routeInstance instanceof EdenPage) {
            $this->routes[$routeInstance->getSlug()] = $routeInstance;
        }
    }

    /**
     * @return array
     */
    public function routes()
    {
        return $this->routes;
    }

    /**
     * @param  string  $slug
     * @return bool
     */
    public function has($slug)
    {
        return isset($this->routes[$slug]);
    }

    /**
     * @param  string  $slug
     * @return mixed|null
     */
    public function get($slug)
    {
        if ($this->has($slug)) {
            return $this->routes[$slug];
        }

        return null;
    }
}
