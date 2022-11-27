<?php

namespace Dgharami\Eden\Components;

class EdenButton extends Menu\MenuItem
{
    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        return view('eden::widgets.button');
    }
}
