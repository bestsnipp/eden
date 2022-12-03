<?php

namespace Dgharami\Eden\Components\DataTable\Actions;

use App\Models\User;
use Dgharami\Eden\Components\EdenPage;
use Dgharami\Eden\Components\EdenResource;
use Dgharami\Eden\Traits\CanBeRendered;
use Faker\Factory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class StaticAction extends Action
{
    public $visibilityOnDetails = true;

    public $icon = 'cursor-click';

    protected string $route = '#';

    protected bool $inNewTab = false;

    protected bool $isForm = false;

    protected bool $formWithCsrf = false;

    protected string $method = 'GET';

    protected array $data = [];

    protected bool $isResource = false;

    public function allowBulk()
    {
        return false;
    }

    public function apply($records, $payload)
    {
        // We don't need this action for this type Action
    }

    /**
     * Show Icon
     *
     * @param \Closure|string $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = appCall($title);
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
            'formWithCsrf' => $this->formWithCsrf
        ];
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view($type, $action, $record, $buttonStyle)
    {
        return view('eden::actions.static', compact('type', 'action', 'record', 'buttonStyle'));
    }

}