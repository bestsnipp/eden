<?php

namespace BestSnipp\Eden\HeaderActions;

use BestSnipp\Eden\Components\HeaderAction;

class ThemeAction extends HeaderAction
{
    public function view()
    {
        return view('eden::header.theme');
    }
}
