<?php

namespace BestSnipp\Eden\Components;

use BestSnipp\Eden\Traits\InteractsWithEdenRoute;
use BestSnipp\Eden\Traits\Makeable;
use BestSnipp\Eden\Traits\WithModel;
use Illuminate\Support\Str;

/**
 * @method static static make($params = [])
 */
abstract class EdenPage
{
    use Makeable;
    use InteractsWithEdenRoute;
    use WithModel;

    protected $slug = '';

    public $title = null;

    protected bool $isTransparent = false;

    public function __construct()
    {
        $this->generateInitialSlug();
    }

    private function generateInitialSlug()
    {
        if (empty($this->slug)) {
            $namespace = app()->getNamespace().config('eden.base', 'Eden').'\Resources\\';
            $classPath = get_called_class();
            $this->slug = Str::slug(Str::snake(Str::replace($namespace, '', $classPath)));
        }
    }

    /**
     * Get Singular form of Title
     *
     * @return string
     */
    public function labelSingular()
    {
        $title = empty($this->title) ? class_basename(get_called_class()) : $this->title;

        return Str::singular(Str::title(Str::snake($title, ' ')));
    }

    /**
     * Get Plural form of Title
     *
     * @return string
     */
    public function labelPlural()
    {
        $title = empty($this->title) ? class_basename(get_called_class()) : $this->title;

        return Str::plural(Str::title(Str::snake($title, ' ')));
    }

    /**
     * @return string
     */
    public function getSlug()
    {
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
        return $this->prepareView(true);
    }

    public function create($slug)
    {
        return $this->prepareView();
    }

    public function edit($slug, $id)
    {
        return $this->prepareView();
    }

    public function show($slug, $id)
    {
        return $this->prepareView();
    }

    protected function viewParams($isPlural = false)
    {
        return [
            'title' => $isPlural ? $this->labelPlural() : $this->labelSingular(),
            'slug' => $this->slug,
            'components' => collect($this->components())->all(),
            'transparent' => $this->isTransparent,
        ];
    }

    /**
     * Generate Generic View for EdenPage
     *
     * @param  string  $title
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    final public function prepareView($isPlural = false)
    {
        return view('eden::eden')
            ->with($this->viewParams($isPlural));
    }
}
