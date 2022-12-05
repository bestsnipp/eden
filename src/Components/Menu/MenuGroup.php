<?php

namespace Dgharami\Eden\Components\Menu;

use Dgharami\Eden\Traits\AuthorizedToSee;
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
    use AuthorizedToSee;

    protected string $title = '';

    protected string $key = '';

    protected string $icon = 'view-grid';

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
     * Show Icon
     *
     * @param \Closure|string $icon
     * @return $this
     */
    public function icon($icon)
    {
        $this->icon = appCall($icon);
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
