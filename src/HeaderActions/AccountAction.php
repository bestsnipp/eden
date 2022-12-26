<?php

namespace BestSnipp\Eden\HeaderActions;

use BestSnipp\Eden\Components\HeaderAction;
use BestSnipp\Eden\Facades\Eden;

class AccountAction extends HeaderAction
{

    public function view()
    {
        return view('eden::header.account')
            ->with('menu', Eden::getAccountMenu());
    }

}
