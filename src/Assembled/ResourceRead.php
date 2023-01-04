<?php

namespace BestSnipp\Eden\Assembled;

use BestSnipp\Eden\Components\Read;
use BestSnipp\Eden\Facades\Eden;
use BestSnipp\Eden\Traits\HasEdenResource;
use Illuminate\Database\Eloquent\Relations\Relation;

class ResourceRead extends Read
{
    use HasEdenResource;

    public $viaRelation = false;

    public $relation = null;

    public $relationModel = null;

    protected function init()
    {
        $this->getResourceData(function ($edenResource) {
            $this->edenResourceObject = $edenResource;
            $this->mapResourceProperties($edenResource, $edenResource->toDetails());
        });
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

        if ($this->viaRelation && !empty($this->relationModel) && !empty($this->relation)) {
            self::$model = is_string($this->relationModel)
                ? app($this->relationModel)->{$this->relation}
                : $this->relationModel->{$this->relation};

            if (empty(self::$model)) {
                $this->show = false;
            }
        }

        $this->title = $params['title'];
        $this->useGlobalActions = $params['useGlobalActions'];
    }

    protected function fields()
    {
        return collect($this->getResourceData(function ($edenResource) {
            return $edenResource->getFields();
        }, []))
            ->reject(function ($field) {
                return ! $field->visibilityOnDetails;
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
                return ! $action->visibilityOnDetails;
            })
            ->all();
    }

    protected function operations()
    {
        $operations = $this->getResourceData(function ($edenResource) {
            return $edenResource->getOperations();
        }, []);

        return collect($operations)
            ->reject(function ($field) {
                return ! $field->visibilityOnDetails;
            })
            ->all();
    }

    protected function view()
    {
        return $this->edenResourceObject->getViewForRead() ?? parent::view();
    }
}
