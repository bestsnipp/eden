<?php

namespace BestSnipp\Eden\Components\Fields;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @method static static make(mixed $name, string $method = null, string $model = null)
 */
class BelongsToMany extends Field
{
    protected $owner = null;

    protected $relation = null;

    protected $resource = null;

    public $visibilityOnIndex = false;

    public $visibilityOnDetails = false;

    public $visibilityOnCreate = false;

    public $visibilityOnUpdate = false;

    protected function __construct($title, $relation = null, $resource = null)
    {
        $key = null;

        if (is_null($relation)) {
            $relation = Str::plural(Str::camel($title));
        }
        $this->relation = $relation;

        if (is_null($resource)) {
            $resource = 'App\\Eden\\Resources\\' . Str::ucfirst(Str::camel(Str::singular($title)));
        }
        $this->resource = $resource;

        parent::__construct($title, $key);
    }

    public function getResource()
    {
        return $this->resource;
    }

    public function getRelation()
    {
        return $this->relation;
    }

    public function view()
    {
        return '';
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
