<?php

namespace BestSnipp\Eden\HeaderActions;

use BestSnipp\Eden\Components\HeaderAction;
use BestSnipp\Eden\Facades\Eden;

class NotificationsAction extends HeaderAction
{

    public function view()
    {
        return view('eden::header.notifications');
    }

}
