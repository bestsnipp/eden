<?php

namespace Dgharami\Eden\Traits;

use Illuminate\View\View;

trait CanBeRendered
{

    /**
     * @return \Illuminate\Contracts\View\View|string
     * @throws \Throwable
     */
    public final function render()
    {
        $view = $this->view();

        if (method_exists($this, 'defaultViewParams')) {
            $defaultViewParams = appCall([$this, 'defaultViewParams']);
            $view->with($defaultViewParams);
        }

        if ($view instanceof View) {
            return $view->render();
        }

        return $view;
    }

}
