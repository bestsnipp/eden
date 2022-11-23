<?php

namespace Dgharami\Eden\Components;

use Dgharami\Eden\Traits\Makeable;
use Illuminate\Support\Str;

/**
 * @method static static make(string|\Closure $slug)
 */
abstract class EdenPage
{
    use Makeable;
    use HasToast;
    use InteractsWithModal;

    protected $slug = '';

    protected $isTransparent = false;

    /**
     * @param string $slug
     */
    public function __construct($slug)
    {
        if (is_callable($slug)) {
            $slug = app()->call($slug);
        }

        $this->slug = (empty($slug)) ? Str::slug($this->slug) : Str::slug($slug);
    }

    /**
     * @return string
     */
    public function label($singular = true)
    {
        $title = Str::title(Str::snake(class_basename(get_called_class()), ' '));
        return $singular ? Str::singular($title) : Str::plural($title);
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @return array
     */
    public function tabs()
    {
        return [];
    }

    /**
     * @return array
     */
    public function cards()
    {
        return [];
    }

    /**
     * @return array
     */
    public function tables()
    {
        return [];
    }

    /**
     * @return array
     */
    public function forms()
    {
        return [];
    }

    /**
     * @return array
     */
    public function read()
    {
        return [];
    }

    /**
     * @return null
     */
    public function render()
    {
        return null;
    }

    public function index($slug)
    {
        return $this->toView();
    }

    public function create($slug)
    {
        return $this->toView();
    }

    public function edit($slug, $id)
    {
        return $this->toView();
    }

    public function show($slug, $id)
    {
        return $this->toView();
    }

    /**
     * @return array
     */
    final public function toView()
    {
        return [
            'title' => $this->label(),
            'slug' => $this->slug,
            'tabs' => collect($this->tabs())->all(),
            'cards' => collect($this->cards())->all(),
            'tables' => collect($this->tables())->all(),
            'forms' => collect($this->forms())->all(),
            'read' => collect($this->read())->all(),
            'transparent' => $this->isTransparent
        ];
    }

}
