<?php

namespace BestSnipp\Eden\Components\Fields;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @method static static make(mixed $name, string $method = null, string $model = null)
 */
class BelongsToMany extends Field
{
    protected $filterable = true;

    protected $withTrashed = false;

    protected $owner = null;

    protected $model = null;

    protected $relation = null;

    protected $ownerKey = 'id';

    protected $value = [];

    public $visibilityOnIndex = false;

    public $visibilityOnDetails = false;

    public $visibilityOnCreate = false;

    public $visibilityOnUpdate = false;

    protected function __construct($title, $relation = null, $resource = null)
    {
        $this->prepareRelation($resource);

        if (is_null($relation)) {
            $relation = Str::plural(Str::camel($title));
        }

        $this->relation = $relation;
        $key = null;
        if (!is_null($this->model)) {
            //$key = $this->model->{$this->relation}()->getForeignKeyName();
            //$this->ownerKey =  $this->model->{$this->relation}()->getOwnerKeyName();
        }

        parent::__construct($title, $key);
    }

    public function onMount()
    {
        $this->markFieldAsRequired();
    }

    protected function prepareRelation($model = null)
    {
        $owners = debug_backtrace();
        $this->owner = collect($owners)->first(function ($trace) {
            return $trace['function'] == 'fields';
        })['object'] ?? null;

        if (empty($model)) {
            if (!is_null($this->owner)) {
                $this->model = !empty($this->owner::$model) ? app($this->owner::$model) : null;
            }
        } else {
            $this->model = app($model::$model) ?? null;
        }
    }

    protected function loadRelationData()
    {
        $allRecordsQuery = $this->model->{$this->relation}()->getRelated();

        if ($this->withTrashed) {
            $allRecordsQuery = $allRecordsQuery->withTrashed();
        }

        $this->options = collect($allRecordsQuery->get())
            ->filter()
            ->pluck('title', $this->ownerKey)
            ->all();

        $this->value = collect($this->value)
            ->filter()
            ->pluck('title', $this->ownerKey)
            ->all();
    }

    public function disableSearchFilter()
    {
        $this->filterable = false;

        return $this;
    }

    public function withTrashed()
    {
        $this->withTrashed = true;

        return $this;
    }


    protected function markFieldAsRequired()
    {
        $this->createRules = collect(array_merge(Arr::wrap($this->createRules), ['required']))->filter()->toArray();
        $this->updateRules = collect(array_merge(Arr::wrap($this->updateRules), ['required']))->filter()->toArray();
        $this->required = true;
    }

    public function view()
    {
        return '';
        $this->meta = array_merge($this->meta, [
            'multiple' => 'multiple'
        ]);
        $this->loadRelationData();

        return view('eden::fields.input.select')
                ->with('searchFilterEnabled', $this->filterable);
    }

    public function viewForIndex()
    {
        return '';
    }

    public function viewForRead()
    {
        return '';
    }
}
