<?php

namespace Dgharami\Eden\Components\Menu;

use Dgharami\Eden\Traits\CanBeRendered;
use Dgharami\Eden\Traits\Makeable;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @method static static make(\Closure|string $title, \Closure|array $items)
 */
class MenuGroup
{
    use Makeable;
    use CanBeRendered;

    protected string $title = '';

    protected string $key = '';

    protected string $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">' .
        '<path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />'.
    '</svg>';

    protected array $items = [];

    /**
     * @param \Closure|string $title
     * @param \Closure|string $items
     */
    protected function __construct($title, $items = [])
    {
        $this->title = appCall($title);
        $this->items = collect(appCall($items))->all();

        $this->key = (empty($this->title)) ? Str::snake(Str::random()) : Str::snake($this->title);
    }


    public function addItem($item)
    {
        $this->items[] = appCall($item);
        return $this;
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        $itemsRoutes = Arr::flatten(collect($this->items)
            ->transform(function ($item) {
                return $item->getPossibleRoutes();
            })
            ->all());

        return view('eden::menu.group')
            ->with([
                'id' => $this->key,
                'icon' => $this->icon,
                'title' => $this->title,
                'items' => $this->items,
                'active' => in_array(url()->current(), $itemsRoutes)
            ]);
    }

}
