<?php

namespace BestSnipp\Eden\Traits;

use Illuminate\View\View;
use Livewire\Component;

trait CanBeRendered
{
    protected $show = true;

    /**
     * @return boolean
     */
    public function shouldShow()
    {
        return $this->show;
    }

    /**
     * @return \Illuminate\Contracts\View\View|string
     * @throws \Throwable
     */
    final public function render()
    {
        if (in_array(AuthorizedToSee::class, class_uses_recursive($this)) && !$this->isAuthorizedToSee()) {
           return '';
        }

        if (!$this->shouldShow()) {
           return '';
        }

        $view = call_user_func_array([$this, 'view'], func_get_args());

        if (method_exists($this, 'edenDefaultViewParams')) {
            $edenDefaultViewParams = appCall([$this, 'edenDefaultViewParams']);
            $view = $view->with($edenDefaultViewParams);
        }

        if (method_exists($this, 'defaultViewParams')) {
            $defaultViewParams = appCall([$this, 'defaultViewParams']);
            $view = $view->with($defaultViewParams);
        }

        if ($view instanceof View && !($this instanceof Component)) { // Only Render if current class is not a Livewire Component
            return $view->render();
        }

        return $view;
    }

}
