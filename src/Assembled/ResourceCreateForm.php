<?php

namespace BestSnipp\Eden\Assembled;

use BestSnipp\Eden\Components\Form;
use BestSnipp\Eden\Traits\HasEdenResource;

class ResourceCreateForm extends Form
{
    use HasEdenResource;

    public $replicateId = null;

    public $isReplicating = null;

    protected $queryString = [
        'replicateId' => ['exclude' => '', 'as' => 'resourceId'],
        'isReplicating' => ['exclude' => '', 'as' => 'replicate'],
    ];

    protected function init()
    {
        $this->getResourceData(function ($edenResource) {
            $this->edenResourceObject = $edenResource;
            $this->mapResourceProperties($edenResource, $edenResource->toForm());
        });
    }

    protected function resolveRecord()
    {
        if (! empty($this->isReplicating) && boolval($this->isReplicating) && ! empty($this->replicateId)) {
            $this->resourceId = $this->replicateId;
        }
        parent::resolveRecord();
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
        $this->styleContainer = $params['styleContainer'];
    }

    protected function fields()
    {
        return collect($this->getResourceData(function ($resource) {
            return $resource->getFields();
        }, []))
            ->reject(function ($field) {
                return ! $field->visibilityOnCreate;
            })
            ->all();
    }

    protected function transform($validated, $all)
    {
        return $this->edenResourceObject->getTransformMethod($validated, $all) ?? parent::transform($validated, $all);
    }

    protected function propertiesToRemove($isUpdate = false)
    {
        return $this->edenResourceObject->getPropertiesToRemoveMethod($isUpdate) ?? parent::propertiesToRemove($isUpdate);
    }

    protected function action($validated = [], $all = [], $transformed = [])
    {
        if ($this->edenResourceObject->hasMethod('action') ?? false) {
            $this->edenResourceObject->callMethod('action', $validated, $all, $transformed);

            return;
        }

        parent::action($validated, $all, $transformed);
    }

    protected function createRecord($validated = [], $all = [], $transformed = [])
    {
        if ($this->edenResourceObject->hasMethod('createRecord') ?? false) {
            $this->edenResourceObject->callMethod('createRecord', $validated, $all, $transformed);

            return;
        }

        parent::createRecord($validated, $all, $transformed);
    }

    protected function updateRecord($validated = [], $all = [], $transformed = [])
    {
        if ($this->edenResourceObject->hasMethod('updateRecord') ?? false) {
            $this->edenResourceObject->callMethod('updateRecord', $validated, $all, $transformed);

            return;
        }

        parent::updateRecord($validated, $all, $transformed);
    }

    protected function onActionCompleted($data)
    {
        if ($this->edenResourceObject->hasMethod('onActionCompleted') ?? false) {
            $this->edenResourceObject->callMethod('onActionCompleted', $data);

            return;
        }

        parent::onActionCompleted($data);
    }

    protected function onActionException(\Exception $exception)
    {
        if ($this->edenResourceObject->hasMethod('onActionException') ?? false) {
            $this->edenResourceObject->callMethod('onActionException', $exception);

            return;
        }

        parent::onActionException($exception);
    }

    public function afterRecordCreated($record)
    {
        if ($this->edenResourceObject->hasMethod('afterRecordCreated') ?? false) {
            $this->edenResourceObject->callMethod('afterRecordCreated', $record);
        }
    }

    public function afterRecordUpdated($record)
    {
        if ($this->edenResourceObject->hasMethod('afterRecordUpdated') ?? false) {
            $this->edenResourceObject->callMethod('afterRecordUpdated', $record);
        }
    }

    protected function view()
    {
        return $this->edenResourceObject->getViewForCreate() ?? parent::view();
    }
}
