<?php

namespace Dgharami\Eden\Components\Metrics;

use Dgharami\Eden\Traits\Makeable;
use Illuminate\View\View;

class ViewMetric extends MetricValue
{
    use Makeable;

    protected $view = null;

    /**
     * @param View $view
     * @return $this
     */
    public function withView($view = null)
    {
        $view = appCall($view);
        $this->view = ($view instanceof View) ? $view : view($view);
        return $this;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        return $this->view;
    }

}
