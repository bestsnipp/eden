<?php

namespace BestSnipp\Eden\Traits;

use BestSnipp\Eden\Facades\Eden;

trait InteractsWithEdenRoute
{

    public $resource = '';

    public $resourceId = '';

    protected $params = ['resource', 'resourceId'];

    // protected $params = ['resource' => 'newSlug', 'resourceId'];

    // protected $params = ['resource' => 'newSlug', 'resourceId' => 'newID'];

    public function bootInteractsWithEdenRoute()
    {
        $edenRoute = Eden::getCurrentRoute();

        if (!is_null($edenRoute)) {
            collect($edenRoute['parameters'] ?? '')
                ->each(function ($item, $key) {
                    if (in_array($key, $this->params) && property_exists($this, $key)) {
                        $this->{$key} = $item;
                    }else if (isset($this->params[$key]) && property_exists($this, $key)) {
                        $propertyToAssign = $this->params[$key];
                        $this->{$propertyToAssign} = $item;
                    }
                });
        }
    }

}
