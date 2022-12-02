<?php

namespace Dgharami\Eden;

use Dgharami\Eden\Components\EdenPage;
use Dgharami\Eden\Components\Modal;
use Dgharami\Eden\Facades\EdenModal;
use Dgharami\Eden\Facades\EdenRoute;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Livewire;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

class EdenManager
{
    protected $menuCallback = null;

    protected $accountMenuCallback = null;

    protected $footerCallback = null;

    protected $actions = [];

    protected $filters = [];

    /**
     * Provide Main Menu Items
     *
     * @return void
     */
    public function mainMenu($callback = null)
    {
        $this->menuCallback = $callback;
    }

    /**
     * Get Main Menu Items
     *
     * @return array
     */
    public function getMainMenu()
    {
        if (!is_null($this->menuCallback) && is_callable($this->menuCallback)) {
            $caller = $this->menuCallback;
            return appCall($caller);
        }
        return [];
    }

    /**
     * Set Account Menu
     *
     * @return void
     */
    public function accountMenu($callback = null)
    {
        $this->accountMenuCallback = $callback;
    }

    /**
     * Get Account Menu Items
     *
     * @return array
     */
    public function getAccountMenu()
    {
        if (!is_null($this->accountMenuCallback) && is_callable($this->accountMenuCallback)) {
            $caller = $this->accountMenuCallback;
            return appCall($caller);
        }
        return [];
    }

    /**
     * Set Global Actions for DataTable
     *
     * @return void
     */
    public function registerActions($actions = [])
    {
        $this->actions = array_merge($this->actions, collect($actions)->all());
    }

    /**
     * Get Global Actions for DataTable
     *
     * @return array
     */
    public function actions()
    {
        return $this->actions;
    }

    /**
     * Set Global Filters for DataTable
     *
     * @return void
     */
    public function registerFilters($filters = [])
    {
        $this->filters = array_merge($this->filters, collect($filters)->all());
    }

    /**
     * Global Filters for DataTable
     *
     * @return array
     */
    public function filters()
    {
        return $this->filters;
    }

    /**
     * Set Footer View
     *
     * @return void
     */
    public function footer($callback = null)
    {
        $this->footerCallback = $callback;
    }

    /**
     * Set Footer View
     *
     * @return mixed
     */
    public function getFooter()
    {
        if (!is_null($this->footerCallback)) {
            $caller = $this->footerCallback;
            return appCall($caller);
        }
        return view('eden::widgets.footer');
    }

    /**
     * All Modals
     *
     * @return array
     */
    public function modals()
    {
        return EdenModal::modals();
    }

    /**
     * Assign route params with EdenComponent
     *
     * @return mixed
     */
    public function getCurrentRoute()
    {
        return collect(json_decode(session()->get('_eden_request_route_current')))
            ->transform(function ($item, $key) {
                return collect($item)->toArray();
            })
            ->toArray();
    }

    /**
     * @param string $directory
     * @return void
     * @throws \ReflectionException
     */
    public function registerComponents($directory, $namespace = null, $basePath = null)
    {
        if (!File::exists($directory)) {
            return;
        }

        if (is_null($namespace)) {
            $namespace = app()->getNamespace();
        }
        if (is_null($basePath)) {
            $basePath = app_path() . DIRECTORY_SEPARATOR;
        }

        foreach ((new Finder())->in($directory)->files() as $component) {
            $component = $namespace.str_replace(
                    ['/', '.php'],
                    ['\\', ''],
                    Str::after($component->getPathname(), $basePath)
                );

            // Bind LiveWire Components
            if (is_subclass_of($component, Component::class) && !(new ReflectionClass($component))->isAbstract()) {
                Livewire::component($component::getName(), $component);
            }

            // Register EdenPages
            if (is_subclass_of($component, EdenPage::class) && !(new ReflectionClass($component))->isAbstract()) {
                EdenRoute::register($component);
            }

            // Register Modals
            if (is_subclass_of($component, Modal::class) && !(new ReflectionClass($component))->isAbstract()) {
                EdenModal::register($component);
            }
        }
    }

}
