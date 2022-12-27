<?php

namespace BestSnipp\Eden\Providers;

use BestSnipp\Eden\Assembled\MediaManager\MediaManagerDataTable;
use BestSnipp\Eden\Assembled\ResourceCreateForm;
use BestSnipp\Eden\Assembled\ResourceDataTable;
use BestSnipp\Eden\Assembled\ResourceEditForm;
use BestSnipp\Eden\Assembled\ResourceRead;
use BestSnipp\Eden\Cards\EdenIntro;
use BestSnipp\Eden\Console\DeveloperCommand;
use BestSnipp\Eden\Console\EdenInstall;
use BestSnipp\Eden\Console\MakeAction;
use BestSnipp\Eden\Console\MakeCard;
use BestSnipp\Eden\Console\MakeDataTable;
use BestSnipp\Eden\Console\MakeEdenPage;
use BestSnipp\Eden\Console\MakeEdenResource;
use BestSnipp\Eden\Console\MakeField;
use BestSnipp\Eden\Console\MakeFilter;
use BestSnipp\Eden\Console\MakeForm;
use BestSnipp\Eden\Console\MakeListMetric;
use BestSnipp\Eden\Console\MakeModal;
use BestSnipp\Eden\Console\MakeProgressMetric;
use BestSnipp\Eden\Console\MakeRead;
use BestSnipp\Eden\Console\MakeSplitMetric;
use BestSnipp\Eden\Console\MakeStatisticMetric;
use BestSnipp\Eden\Console\MakeTrendMetric;
use BestSnipp\Eden\Console\MakeViewMetric;
use BestSnipp\Eden\Events\EdenServiceProviderRegistered;
use BestSnipp\Eden\Exceptions\EdenExceptionHandler;
use BestSnipp\Eden\Facades\Eden;
use BestSnipp\Eden\Facades\EdenAssets;
use BestSnipp\Eden\Facades\EdenModal;
use BestSnipp\Eden\HeaderActions\AccountAction;
use BestSnipp\Eden\HeaderActions\NotificationsAction;
use BestSnipp\Eden\HeaderActions\ThemeAction;
use BestSnipp\Eden\Listeners\PrepareEden;
use BestSnipp\Eden\Middleware\EdenRequestHandler;
use BestSnipp\Eden\Modals\DeleteModal;
use BestSnipp\Eden\Modals\MediaModal;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class EdenCoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (! $this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__.'/../Config/eden.php', 'eden');
        }
        if (config('eden.media_manager')) {
            $this->loadMigrationsFrom(__DIR__.'/../Migrations');
        }

        $this->registerPersistentMiddleware();
        $this->registerMacos();
        $this->registerRoutes();

        $this->registerEvents();
        $this->registerFacades();
        $this->registerCommands();
        $this->loadViews();

        $this->registerStyleAndScripts();
        $this->publishResources();

        // Load Pluggable Resources
        Event::dispatch(EdenServiceProviderRegistered::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Register All Events
     *
     * @return void
     */
    protected function registerEvents()
    {
        $this->app->bind(ExceptionHandler::class, EdenExceptionHandler::class);
        tap($this->app['events'], function ($event) {
            $event->listen(EdenServiceProviderRegistered::class, [PrepareEden::class, 'handle']);
        });
    }

    /**
     * Assign variables via view compose
     *
     * @return void
     */
    protected function prepareViewComposes()
    {
        View::composer('eden::menu.index', function ($view) {
            return $view->with('menu', Eden::getMainMenu());
        });
        View::composer('eden::widgets.header-right', function ($view) {
            return $view->with('menu', Eden::getAccountMenu());
        });
    }

    /**
     * Publish Eden Specific Files
     *
     * @return void
     */
    protected function publishResources()
    {
        $this->publishes([
            __DIR__.'/../stubs/EdenServiceProvider.stub' => app_path('Providers/EdenServiceProvider.php'),
        ], 'eden-provider');

        $this->publishes([
            __DIR__.'/../Config/eden.php' => config_path('eden.php'),
        ], 'eden-config');

        $this->publishes([
            __DIR__.'/../../public' => public_path('vendor/eden'),
        ], ['laravel-assets', 'eden-assets']);
    }

    /**
     * Register Eden Macros
     *
     * @return void
     */
    protected function registerMacos()
    {
        if (! Arr::hasMacro('toHtmlAttribute')) {
            Arr::macro('toHtmlAttribute', function ($arr) {
                if (empty($arr)) {
                    return '';
                }

                $compiled = implode('="%s" ', array_keys($arr)).'="%s"';

                return vsprintf($compiled, array_map('htmlspecialchars', array_values($arr)));
            });
        }
    }

    /**
     * Register Eden routes file
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::middlewareGroup('eden', config('eden.middleware', []));
        Route::middleware('eden')
            ->prefix(config('eden.entry'))
            ->group(__DIR__.'/../routes/web.php');
    }

    /**
     * Register Eden Facades
     *
     * @return void
     */
    protected function registerFacades()
    {
        $this->app->singleton('edenRouteManager', function () {
            return new \BestSnipp\Eden\RouteManager();
        });
        $this->app->singleton('edenModalManager', function () {
            return new \BestSnipp\Eden\ModalManager();
        });
        $this->app->singleton('eden', function () {
            return new \BestSnipp\Eden\EdenManager();
        });
        $this->app->singleton('edenIconManager', function () {
            return new \BestSnipp\Eden\IconManager();
        });
        $this->app->singleton('edenAssetsManager', function () {
            return new \BestSnipp\Eden\AssetsManager();
        });
    }

    /**
     * Register the console commands of this package
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                DeveloperCommand::class,
                // Install
                EdenInstall::class,

                // Cards
                MakeCard::class,
                MakeStatisticMetric::class,
                MakeProgressMetric::class,
                MakeTrendMetric::class,
                MakeSplitMetric::class,
                MakeListMetric::class,
                MakeViewMetric::class,

                // Page & Resource
                MakeEdenResource::class,
                MakeEdenPage::class,

                // DataTables, Forms, Read, Modal
                MakeDataTable::class,
                MakeForm::class,
                MakeRead::class,
                MakeModal::class,

                // Fields, Actions, Filters
                MakeField::class,
                MakeAction::class,
                MakeFilter::class,
            ]);
        }
    }

    /**
     * Register middlewares for livewire render
     *
     * @return void
     */
    protected function registerPersistentMiddleware(): void
    {
        Livewire::addPersistentMiddleware([
            EdenRequestHandler::class,
        ]);
    }

    /**
     * Load package specific views
     *
     * @return void
     */
    protected function loadViews()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'eden');

        $this->loadComponents();
        $this->prepareViewComposes();
    }

    /**
     * Auto Register Components from Package and App
     *
     * @return void
     *
     * @throws \ReflectionException
     */
    protected function loadComponents()
    {
        // Register Package Components
        Livewire::component(ResourceDataTable::getName(), ResourceDataTable::class);
        Livewire::component(ResourceCreateForm::getName(), ResourceCreateForm::class);
        Livewire::component(ResourceEditForm::getName(), ResourceEditForm::class);
        Livewire::component(ResourceRead::getName(), ResourceRead::class);
        Livewire::component(MediaManagerDataTable::getName(), MediaManagerDataTable::class);

        // Cards
        Livewire::component(EdenIntro::getName(), EdenIntro::class);

        // Header Actions
        Livewire::component(AccountAction::getName(), AccountAction::class);
        Livewire::component(NotificationsAction::getName(), NotificationsAction::class);
        Livewire::component(ThemeAction::getName(), ThemeAction::class);

        // Register Predefined Modals
        if (config('eden.media_manager')) {
            Livewire::component(MediaModal::getName(), MediaModal::class);
            EdenModal::register(MediaModal::class);
        }

        Livewire::component(DeleteModal::getName(), DeleteModal::class);
        EdenModal::register(DeleteModal::class);

        // Auto Discover Components on App/Eden Folder
        Eden::registerComponents(app_path('Eden'));
    }

    /**
     * Register Eden specific Styles and Scripts
     *
     * @return void
     */
    protected function registerStyleAndScripts()
    {
        // jQuery
        EdenAssets::registerScript('https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.min.js', 'jquery');

        // ApexChart
        EdenAssets::registerScript('https://cdn.jsdelivr.net/npm/apexcharts', 'apexcharts');

        // Trix
        EdenAssets::registerStyle('https://cdn.jsdelivr.net/npm/trix@2.0.1/dist/trix.css', 'trix');
        EdenAssets::registerScript('https://cdn.jsdelivr.net/npm/trix@2.0.1/dist/trix.umd.min.js', 'trix');

        // FlatPickr
        EdenAssets::registerStyle('https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css', 'flatpickr');
        EdenAssets::registerScript('https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js', 'flatpickr');

        // Pickr
        EdenAssets::registerStyle('https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.8.2/dist/themes/nano.min.css', 'pickr');
        EdenAssets::registerScript('https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.8.2/dist/pickr.min.js', 'pickr');

        // Select 2
        EdenAssets::registerStyle('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', 'select2');
        EdenAssets::registerScript('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', 'select2');

        // ToolTip - Alpine
        EdenAssets::registerStyle('https://unpkg.com/tippy.js@6/dist/tippy.css', 'tippy');
        EdenAssets::registerScript('https://cdn.jsdelivr.net/npm/@ryangjchandler/alpine-tooltip@1.2.0/dist/cdn.min.js', 'alpine-tooltip');

        // NiceScroll
        EdenAssets::registerScript('https://cdn.jsdelivr.net/npm/jquery.nicescroll@3.7.6/dist/jquery.nicescroll.min.js', 'nicescroll');

        // CodeMirror
        EdenAssets::registerStyle('https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.css', 'codemirror');
        EdenAssets::registerStyle('https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/theme/monokai.min.css', 'codemirror-monokai');
        EdenAssets::registerScript('https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/codemirror.min.js', 'codemirror');
        EdenAssets::registerScript('https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/javascript/javascript.min.js', 'codemirror-javascript');
        EdenAssets::registerScript('https://cdnjs.cloudflare.com/ajax/libs/codemirror/6.65.7/mode/css/css.min.js', 'codemirror-css');
    }
}
