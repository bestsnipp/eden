<?php

namespace Dgharami\Eden\Assembled;

use Dgharami\Eden\Components\Read;
use Dgharami\Eden\Facades\Eden;
use Dgharami\Eden\Traits\HasEdenResource;
use Illuminate\Support\Str;

class ResourceRead extends Read
{
    use HasEdenResource;

    protected function init()
    {
        $edenResource = app($this->edenResource);
        if (!is_null($edenResource)) {
            $this->mapResourceProperties($edenResource, $edenResource->toDetails());
        }
    }

    public function mount()
    {
        $this->init();
        parent::mount();
    }

    public function hydrate()
    {
        $this->init();
        parent::hydrate();
    }

    public function updated()
    {
        $this->init();
        parent::hydrate();
    }

    protected function mapResourceProperties($edenResource, $params = [])
    {
        self::$model = $edenResource::$model;
        $this->title = $params['title'];
        $this->useGlobalActions = $params['useGlobalActions'];
    }

    protected function fields()
    {
        return collect($this->getResourceData(function ($edenResource) {
            return $edenResource->getFields();
        }, []))
            ->reject(function ($field) {
                return !$field->visibilityOnDetails;
            })
            ->all();
    }

    protected function actions()
    {
        $globalActions = $this->useGlobalActions ? Eden::actions() : [];
        return collect($this->getResourceData(function ($edenResource) use ($globalActions) {
            return array_merge($edenResource->getActions(), $globalActions);
        }, []))
            ->reject(function ($action) {
                return !$action->visibilityOnDetails;
            })
            ->all();
    }

}