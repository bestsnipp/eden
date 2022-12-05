<?php

namespace Dgharami\Eden\Components\Menu;

use Dgharami\Eden\Traits\AuthorizedToSee;
use Dgharami\Eden\Traits\CanBeRendered;
use Dgharami\Eden\Traits\Makeable;

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
     * @param \Closure|string $title
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
