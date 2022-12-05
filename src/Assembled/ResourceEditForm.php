<?php

namespace Dgharami\Eden\Assembled;


class ResourceEditForm extends ResourceCreateForm
{
    public function boot()
    {
        $this->isUpdate = true; // Force the form as Update Form
    }

    protected function fields()
    {
        return collect($this->getResourceData(function ($resource) {
                return $resource->getFields();
            }, []))
            ->reject(function ($field) {
                return !$field->visibilityOnUpdate;
            })
            ->all();
    }

    protected function view()
    {
        return $this->edenResourceObject->getViewForEdit() ?? parent::view();
    }

}
