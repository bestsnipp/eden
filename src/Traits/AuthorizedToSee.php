<?php

namespace Dgharami\Eden\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;

trait AuthorizedToSee
{
    /**
     * Callback to check the authorization status
     *
     * @var null
     */
    protected $canSeeCallback = null;

    /**
     * Check if is authorized to see or not
     *
     * @return bool|mixed
     */
    public function isAuthorizedToSee()
    {
        return !is_null($this->canSeeCallback) ? appCall($this->canSeeCallback) : true;
    }

    /**
     * Set the callback to check is authorized or not
     *
     * @return $this
     */
    public function canSee($callback)
    {
        $this->canSeeCallback = $callback;
        return $this;
    }

    /**
     * Check authorization based on Policy
     *
     * @return $this
     */
    public function canSeeWhen($ability, $arguments)
    {
        $modelToCheck = collect(Arr::wrap($arguments));

        if (!is_null(Gate::getPolicyFor($modelToCheck->first()))) { // Policy Available for the model/class
            return $this->canSee(function () use ($ability, $arguments) {
                return auth()->user()->can($ability, $arguments);
            });
        }

        return $this;
    }
}
