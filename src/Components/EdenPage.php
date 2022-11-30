<?php

namespace Dgharami\Eden\Components;

use Dgharami\Eden\Traits\Makeable;
use Illuminate\Support\Str;

/**
 * @method static static make($params = [])
 */
abstract class EdenPage
{
    use Makeable;

    protected $slug = '';

    protected bool $isTransparent = false;

    /**
     * Get Singular form of Title
     *
     * @return string
     */
    public function labelSingular()
    {
        return Str::singular(Str::title(Str::snake(class_basename(get_called_class()), ' ')));
    }

    /**
     * Get Plural form of Title
     *
     * @return string
     */
    public function labelPlural()
    {
        return Str::plural(Str::title(Str::snake(class_basename(get_called_class()), ' ')));
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        if (empty($this->slug)) {
            $this->slug = Str::random(32);
        }
        return Str::slug(Str::snake($this->slug));
    }

    /**
     * @return array
     */
    public function components()
    {
        return [];
    }

    public function index($slug)
    {
        return $this->prepareView($this->labelPlural());
    }

    public function create($slug)
    {
        return $this->prepareView($this->labelSingular());
    }

    public function edit($slug, $id)
    {
        return $this->prepareView($this->labelSingular());
    }

    public function show($slug, $id)
    {
        return $this->prepareView($this->labelSingular());
    }

    /**
     * @return array
     */
    final public function prepareView($title = '')
    {
        return [
            'title' => $title,
            'slug' => $this->slug,
            'components' => collect($this->components())->all(),
            'transparent' => $this->isTransparent
        ];
    }

}
