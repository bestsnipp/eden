<?php

namespace BestSnipp\Eden;

use BestSnipp\Eden\Components\EdenPage;
use BestSnipp\Eden\Components\Modal;

class ModalManager
{
    private $modals = [];

    /**
     * @param mixed $modalComponent
     * @return void
     */
    public function register($modalComponent)
    {
        if (is_subclass_of($modalComponent, Modal::class) && !isset($this->modals[$modalComponent::getName()])) {
            $this->modals[$modalComponent::getName()] = $modalComponent::make();
        }
    }

    /**
     * @return array
     */
    public function modals()
    {
        return $this->modals;
    }

    /**
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->modals[$name]);
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    public function get($name)
    {
        if ($this->has($name)) {
            return $this->modals[$name];
        }
        return null;
    }

}
