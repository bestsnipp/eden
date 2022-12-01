<?php

namespace Dgharami\Eden\Components\Menu;

use Dgharami\Eden\Components\EdenPage;
use Dgharami\Eden\Components\EdenResource;
use Dgharami\Eden\Components\PageView;
use Dgharami\Eden\Components\Resource;
use Dgharami\Eden\RouteManager;
use Dgharami\Eden\Traits\CanBeRendered;
use Dgharami\Eden\Traits\HasTitleKey;
use Dgharami\Eden\Traits\HasView;
use Dgharami\Eden\Traits\Makeable;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

/**
 * @method static static make(string|\Closure $title)
 */
class MenuItem
{
    use Makeable;
    use CanBeRendered;

    protected string $title = '';

    protected string $key = '';

    protected ?string $icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 scale-75" fill="currentColor" viewBox="0 0 512 512">' .
        '<path d="M512 256C512 397.4 397.4 512 256 512C114.6 512 0 397.4 0 256C0 114.6 114.6 0 256 0C397.4 0 512 114.6 512 256zM256 48C141.1 48 48 141.1 48 256C48 370.9 141.1 464 256 464C370.9 464 464 370.9 464 256C464 141.1 370.9 48 256 48z"/>' .
    '</svg>';

    protected string $route = '#';

    protected bool $inNewTab = false;

    protected bool $isForm = false;

    protected bool $formWithCsrf = false;

    protected string $method = 'GET';

    protected array $data = [];

    protected bool $isResource = false;

    /**
     * Create a Menu Item
     *
     * @param \Closure|string $title
     * @param \Closure|string $route
     */
    protected function __construct($title)
    {
        $this->title = appCall($title);
        $this->key = (empty($this->title)) ? Str::snake(Str::random()) : Str::snake($this->title);
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
     * Hide Icon
     *
     * @return $this
     */
    public function noIcon()
    {
        $this->icon = null;
        return $this;
    }

    /**
     * Open link in new tab
     *
     * @return $this
     */
    public function openInNewTab($should = true)
    {
        $this->inNewTab = appCall($should);
        return $this;
    }

    /**
     * Provide a Route
     *
     * @param string $name
     * @param array $parameters
     * @param bool $absolute
     * @return $this
     */
    public function route($name, $parameters = [], $absolute = true)
    {
        $this->route = route($name, $parameters, $absolute);
        return $this;
    }

    /**
     * Provide a Path
     *
     * @param \Closure|string $path
     * @return $this
     */
    public function path($path)
    {
        $this->route = appCall($path);
        return $this;
    }

    /**
     * Provide a External Link
     *
     * @param \Closure|string $url
     * @return $this
     */
    public function external($url)
    {
        $this->route = appCall($url);
        return $this;
    }

    /**
     * Link to a Resource
     *
     * @param EdenResource|\Closure $resource
     * @return $this
     */
    public function resource($resource)
    {
        if (is_subclass_of($resource, EdenResource::class)) {
            $this->route = route('eden.page', appCall($resource)->getSlug());
        }
        return $this;
    }

    /**
     * Link to a EdenPage
     *
     * @param EdenPage|\Closure $page
     * @return $this
     */
    public function edenPage($page)
    {
        if (is_subclass_of($page, EdenPage::class)) {
            $this->route = route('eden.page', appCall($page)->getSlug());
        }
        return $this;
    }

    /**
     * Use as Form Request
     *
     * @param \Closure $page
     * @return $this
     */
    public function viaForm($method = 'POST', $data = [], $includeCsrf = true)
    {
        $this->isForm = true;
        $this->formWithCsrf = $includeCsrf;
        $this->method = appCall($method);
        $this->data = appCall($data);
        return $this;
    }


    public function getPossibleRoutes()
    {
        $possibilies = [];

        if ($this->isResource) {
            $currentRoute = Route::current();
            $routeParams = $currentRoute->parameters();
            if (isset($routeParams['slug'])) {
                $possibilies[] = route('eden.page', $this->slug);
                $possibilies[] = route('eden.create', $this->slug);
                if (isset($routeParams['id']) && isset($routeParams['slug']) && trim($routeParams['slug']) == trim($this->slug)) {
                    $possibilies[] = route('eden.show', ['slug' => $this->slug, 'id' => $routeParams['id']]);
                    $possibilies[] = route('eden.edit', ['slug' => $this->slug, 'id' => $routeParams['id']]);
                }
            }
        } else {
            $possibilies[] = $this->route;
        }

        return $possibilies;
    }

    public function defaultViewParams()
    {
        return [
            'icon' => $this->icon,
            'title' => $this->title,
            'route' => $this->route,
            'inNewTab' => $this->inNewTab,
            'isForm' => $this->isForm,
            'method' => $this->method,
            'data' => $this->data,
            'formWithCsrf' => $this->formWithCsrf,
            'active' => in_array(url()->current(), $this->getPossibleRoutes())
        ];
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view()
    {
        return view('eden::menu.item');
    }

}
