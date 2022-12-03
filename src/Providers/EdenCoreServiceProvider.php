<?php
namespace Dgharami\Eden\Providers;

use Dgharami\Eden\Assembled\ResourceCreateForm;
use Dgharami\Eden\Assembled\ResourceDataTable;
use Dgharami\Eden\Assembled\ResourceEditForm;
use Dgharami\Eden\Assembled\ResourceRead;
use Dgharami\Eden\Console\DeveloperCommand;
use Dgharami\Eden\Console\EdenInstall;
use Dgharami\Eden\Console\MakeAction;
use Dgharami\Eden\Console\MakeCard;
use Dgharami\Eden\Console\MakeEdenPage;
use Dgharami\Eden\Console\MakeEdenResource;
use Dgharami\Eden\Console\MakeListMetric;
use Dgharami\Eden\Console\MakeProgressMetric;
use Dgharami\Eden\Console\MakeSplitMetric;
use Dgharami\Eden\Console\MakeStatisticMetric;
use Dgharami\Eden\Console\MakeTrendMetric;
use Dgharami\Eden\Console\MakeViewMetric;
use Dgharami\Eden\Events\EdenServiceProviderRegistered;
use Dgharami\Eden\Exceptions\EdenExceptionHandler;
use Dgharami\Eden\Facades\Eden;
use Dgharami\Eden\Facades\EdenAssets;
use Dgharami\Eden\Listeners\PrepareEden;
use Dgharami\Eden\Middleware\EdenRequestHandler;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use Illuminate\Contracts\Debug\ExceptionHandler;

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
            $this->mergeConfigFrom(__DIR__ . '/../Config/eden.php', 'eden');
        }

        $this->registerPersistentMiddleware();
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
            __DIR__.'/../Config/eden.php' => config_path('eden.php')
        ], ['config', 'eden-config']);
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
            ->group(__DIR__ . '/../routes/web.php');
    }

    /**
     * Register Eden Facades
     *
     * @return void
     */
    protected function registerFacades()
    {
        $this->app->singleton('edenRouteManager', function () {
            return new \Dgharami\Eden\RouteManager();
        });
        $this->app->singleton('edenModalManager', function () {
            return new \Dgharami\Eden\ModalManager();
        });
        $this->app->singleton('eden', function () {
            return new \Dgharami\Eden\EdenManager();
        });
        $this->app->singleton('edenIconManager', function () {
            return new \Dgharami\Eden\IconManager();
        });
        $this->app->singleton('edenAssetsManager', function () {
            return new \Dgharami\Eden\AssetsManager();
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

                // Fields, Actions, Filters
                MakeAction::class,
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
            EdenRequestHandler::class
        ]);
    }

    /**
     * Load package specific views
     *
     * @return void
     */
    protected function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'eden');

        $this->loadComponents();
        $this->prepareViewComposes();
    }

    /**
     * Auto Register Components from Package and App
     *
     * @return void
     * @throws \ReflectionException
     */
    protected function loadComponents()
    {
        // Register Package Components
        //Livewire::component(TabGroup::getName(), TabGroup::class);
        Livewire::component(ResourceDataTable::getName(), ResourceDataTable::class);
        Livewire::component(ResourceCreateForm::getName(), ResourceCreateForm::class);
        Livewire::component(ResourceEditForm::getName(), ResourceEditForm::class);
        Livewire::component(ResourceRead::getName(), ResourceRead::class);

        // Auto Register Components that is inside \App\Http\Eden Folder
//        Eden::registerComponents(
//            dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Modals',
//            __NAMESPACE__. '\\',
//            dirname(__FILE__) . DIRECTORY_SEPARATOR
//        );
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
        EdenAssets::registerScripts('https://cdn.jsdelivr.net/npm/jquery@3.6.1/dist/jquery.min.js', 'jquery');

        // ApexChart
        EdenAssets::registerScripts('https://cdn.jsdelivr.net/npm/apexcharts', 'apexcharts');

        // Trix
        EdenAssets::registerStyle('https://cdn.jsdelivr.net/npm/trix@2.0.1/dist/trix.css', 'trix');
        EdenAssets::registerScripts('https://cdn.jsdelivr.net/npm/trix@2.0.1/dist/trix.umd.min.js', 'trix');

        // FlatPickr
        EdenAssets::registerStyle('https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.css', 'flatpickr');
        EdenAssets::registerScripts('https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js', 'flatpickr');

        // Pickr
        EdenAssets::registerStyle('https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.8.2/dist/themes/nano.min.css', 'pickr');
        EdenAssets::registerScripts('https://cdn.jsdelivr.net/npm/@simonwep/pickr@1.8.2/dist/pickr.min.js', 'pickr');

        // Select 2
        EdenAssets::registerStyle('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css', 'select2');
        EdenAssets::registerScripts('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', 'select2');

        // ToolTip - Alpine
        EdenAssets::registerStyle('https://unpkg.com/tippy.js@6/dist/tippy.css', 'tippy');
        EdenAssets::registerScripts('https://cdn.jsdelivr.net/npm/@ryangjchandler/alpine-tooltip@1.2.0/dist/cdn.min.js', 'alpine-tooltip');

        // NiceScroll
        EdenAssets::registerScripts('https://cdn.jsdelivr.net/npm/jquery.nicescroll@3.7.6/dist/jquery.nicescroll.min.js', 'nicescroll');
    }
}
