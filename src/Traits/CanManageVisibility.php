<?php

namespace BestSnipp\Eden\Traits;

trait CanManageVisibility
{
    public $visibilityOnIndex = true;

    public $visibilityOnCreate = true;

    public $visibilityOnUpdate = true;

    public $visibilityOnDetails = true;

    public $visibilityOnModal = true;

    public function showOnIndex($should = true)
    {
        $this->visibilityOnIndex = appCall($should);

        return $this;
    }

    public function showOnCreate($should = true)
    {
        $this->visibilityOnCreate = appCall($should);

        return $this;
    }

    public function showOnUpdate($should = true)
    {
        $this->visibilityOnUpdate = appCall($should);

        return $this;
    }

    public function showOnDetails($should = true)
    {
        $this->visibilityOnDetails = appCall($should);

        return $this;
    }

    public function showOnModal($should = true)
    {
        $this->visibilityOnModal = appCall($should);

        return $this;
    }

    public function hideOnIndex($should = true)
    {
        $this->visibilityOnIndex = ! appCall($should);

        return $this;
    }

    public function hideOnCreate($should = true)
    {
        $this->visibilityOnCreate = ! appCall($should);

        return $this;
    }

    public function hideOnUpdate($should = true)
    {
        $this->visibilityOnUpdate = ! appCall($should);

        return $this;
    }

    public function hideOnDetails($should = true)
    {
        $this->visibilityOnDetails = ! appCall($should);

        return $this;
    }

    public function hideOnModal($should = true)
    {
        $this->visibilityOnModal = ! appCall($should);

        return $this;
    }

    public function onlyOnIndex($callback = true)
    {
        $should = appCall($callback);
        if ($should) {
            $this->visibilityOnIndex = true;
            $this->visibilityOnCreate = false;
            $this->visibilityOnUpdate = false;
            $this->visibilityOnDetails = false;
            $this->visibilityOnModal = false;
        }

        return $this;
    }

    public function onlyOnCreate($callback = true)
    {
        $should = appCall($callback);
        if ($should) {
            $this->visibilityOnIndex = false;
            $this->visibilityOnCreate = true;
            $this->visibilityOnUpdate = false;
            $this->visibilityOnDetails = false;
            $this->visibilityOnModal = false;
        }

        return $this;
    }

    public function onlyOnUpdate($callback = true)
    {
        $should = appCall($callback);
        if ($should) {
            $this->visibilityOnIndex = false;
            $this->visibilityOnCreate = false;
            $this->visibilityOnUpdate = true;
            $this->visibilityOnDetails = false;
            $this->visibilityOnModal = false;
        }

        return $this;
    }

    public function onlyOnDetails($callback = true)
    {
        $should = appCall($callback);
        if ($should) {
            $this->visibilityOnIndex = false;
            $this->visibilityOnCreate = false;
            $this->visibilityOnUpdate = false;
            $this->visibilityOnDetails = true;
            $this->visibilityOnModal = false;
        }

        return $this;
    }

    public function onlyOnModal($callback = true)
    {
        $should = appCall($callback);
        if ($should) {
            $this->visibilityOnIndex = false;
            $this->visibilityOnCreate = false;
            $this->visibilityOnUpdate = false;
            $this->visibilityOnDetails = false;
            $this->visibilityOnModal = true;
        }

        return $this;
    }

    public function exceptOnIndex($callback = true)
    {
        $should = appCall($callback);
        if ($should) {
            $this->visibilityOnIndex = false;
            $this->visibilityOnCreate = true;
            $this->visibilityOnUpdate = true;
            $this->visibilityOnDetails = true;
            $this->visibilityOnModal = true;
        }

        return $this;
    }

    public function exceptOnCreate($callback = true)
    {
        $should = appCall($callback);
        if ($should) {
            $this->visibilityOnIndex = true;
            $this->visibilityOnCreate = false;
            $this->visibilityOnUpdate = true;
            $this->visibilityOnDetails = true;
            $this->visibilityOnModal = true;
        }

        return $this;
    }

    public function exceptOnUpdate($callback = true)
    {
        $should = appCall($callback);
        if ($should) {
            $this->visibilityOnIndex = true;
            $this->visibilityOnCreate = true;
            $this->visibilityOnUpdate = false;
            $this->visibilityOnDetails = true;
            $this->visibilityOnModal = true;
        }

        return $this;
    }

    public function exceptOnDetails($callback = true)
    {
        $should = appCall($callback);
        if ($should) {
            $this->visibilityOnIndex = true;
            $this->visibilityOnCreate = true;
            $this->visibilityOnUpdate = true;
            $this->visibilityOnDetails = false;
            $this->visibilityOnModal = true;
        }

        return $this;
    }

    public function exceptOnModal($callback = true)
    {
        $should = appCall($callback);
        if ($should) {
            $this->visibilityOnIndex = true;
            $this->visibilityOnCreate = true;
            $this->visibilityOnUpdate = true;
            $this->visibilityOnDetails = true;
            $this->visibilityOnModal = false;
        }

        return $this;
    }
}
