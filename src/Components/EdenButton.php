<?php

namespace Dgharami\Eden\Components;

use Dgharami\Eden\Traits\CanManageVisibility;

class EdenButton extends Menu\MenuItem
{
    use CanManageVisibility;

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        return view('eden::widgets.button');
    }
}
