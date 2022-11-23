<?php

namespace Dgharami\Eden\Components;

class EdenButton extends Menu\MenuItem
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        return view('eden::widgets.button')
            ->with([
                'icon' => $this->icon,
                'title' => $this->title,
                'route' => $this->route,
                'inNewTab' => $this->inNewTab,
                'isForm' => $this->isForm,
                'method' => $this->method,
                'data' => $this->data,
                'formWithCsrf' => $this->formWithCsrf,
                'active' => in_array(url()->current(), $this->getPossibleRoutes())
            ]);
    }
}
