<?php

namespace Dgharami\Eden\Assembled;

use Dgharami\Eden\Components\Form;
use Dgharami\Eden\Traits\HasEdenResource;
use Illuminate\Support\Str;

class ResourceCreateForm extends Form
{
    use HasEdenResource;

    public $key = null;

    public $replicate = null;

    protected $queryString = ['key', 'replicate'];

    protected function init()
    {
        $edenResource = app($this->edenResource);
        if (!is_null($edenResource)) {
            $this->mapResourceProperties($edenResource, $edenResource->toForm());
        }
    }

    public function mount()
    {
        $this->init();

        // RowsPerPage
        $this->getResourceData(function ($edenResource) {
            $this->rowsPerPage = $edenResource->rowsPerPage;
        });
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
        $this->isUpdate = $params['isUpdate'];
        $this->styleContainer = $params['styleContainer'];
    }

    protected function fields()
    {
        return collect($this->getResourceData(function ($resource) {
                return $resource->getFields();
            }, []))
            ->reject(function ($field) {
                return !$field->visibilityOnCreate;
            })
            ->all();
    }

}
