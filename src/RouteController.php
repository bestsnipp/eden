<?php

namespace Dgharami\Eden;

use App\Http\Controllers\Controller;
use Dgharami\Eden\Components\EdenPage;
use Dgharami\Eden\Components\Resource;
use Dgharami\Eden\Facades\EdenRoute;
use Illuminate\Support\Facades\Gate;

class RouteController extends Controller
{

    /**
     * This is entry page, but it will redirect you to one of the component Either EdenPage / Resource
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function entry()
    {
        return redirect()->route('eden.page', config('eden.entry_component'));
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function index($slug)
    {
        $resource = $this->checkSlugValidity($slug);
        return $resource->index($slug);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function create($slug)
    {
        $resource = $this->checkSlugValidity($slug);
        return $resource->create($slug);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function show($slug, $id)
    {
        $resource = $this->checkSlugValidity($slug);
        return $resource->show($slug, $id);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function edit($slug, $id)
    {
        $resource = $this->checkSlugValidity($slug);
        return $resource->edit($slug, $id);
    }

    /**
     * @param $slug
     * return PageView|Resource|mixed|null
     */
    public function checkSlugValidity($slug)
    {
        abort_if(!EdenRoute::has($slug), 404);
        $page = EdenRoute::get($slug);

        if (!($page instanceof EdenPage || $page instanceof Resource)) {
            abort(422,  sprintf('Invalid Resource Provided, Resource Must be a %1$s %2$s and provided %3$s', EdenPage::class, Resource::class, gettype($page)));
        }

        return $page;
    }

}
