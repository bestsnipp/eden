<?php

namespace Dgharami\Eden;

class RouteManager
{
    private $routes = [];

    /**
     * @param mixed $route
     * @return void
     */
    public function register($route)
    {
        $this->routes[$route->getSlug()] = $route;
    }

    /**
     * @return array
     */
    public function routes()
    {
        return $this->routes;
    }

    /**
     * @param string $slug
     * @return bool
     */
    public function has($slug)
    {
        return isset($this->routes[$slug]);
    }

    /**
     * @param string $slug
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
