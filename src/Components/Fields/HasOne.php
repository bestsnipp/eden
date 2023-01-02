<?php

namespace BestSnipp\Eden\Components\Fields;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @method static static make(mixed $name, string $method = null, string $model = null)
 */
class HasOne extends BelongsToMany
{
    protected $ownerKey = 'id';

    public function _onMount()
    {
        //$model = $this->resource::$model;
        $owners = debug_backtrace();
        $owner = collect($owners)->first(function ($trace) {
            return $trace['function'] == 'fields';
        })['object'] ?? null;
        $model = $owner::$model ?? null;

        if (!is_null($model)) {
            $model = app($model);
            dd($model->{$this->relation}()->toSql());
            $this->key = $model->{$this->relation}()->getForeignKeyName();
            $this->ownerKey = $model->{$this->relation}()->getOwnerKeyName();
        }
    }

}
