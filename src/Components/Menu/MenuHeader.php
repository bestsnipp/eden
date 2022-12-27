<?php

namespace BestSnipp\Eden\Components\Menu;

use BestSnipp\Eden\Traits\AuthorizedToSee;
use BestSnipp\Eden\Traits\CanBeRendered;
use BestSnipp\Eden\Traits\Makeable;

/**
 * @method static static make(\Closure|string $title)
 */
class MenuHeader
{
    use Makeable;
    use CanBeRendered;
    use AuthorizedToSee;

    protected string $title;

    /**
     * @param  \Closure|string  $title
     */
    protected function __construct($title)
    {
        $this->title = appCall($title);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        return view('eden::menu.header')->with('title', $this->title);
    }
}
