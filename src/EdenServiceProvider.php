<?php
namespace Dgharami\Eden;

use App\Models\User;
use Dgharami\Eden\Assembled\ResourceCreateForm;
use Dgharami\Eden\Assembled\ResourceDataTable;
use Dgharami\Eden\Assembled\ResourceEditForm;
use Dgharami\Eden\Assembled\ResourceRead;
use Dgharami\Eden\Console\DeveloperCommand;
use Dgharami\Eden\Console\MakeCard;
use Dgharami\Eden\Console\MakeEdenPage;
use Dgharami\Eden\Facades\Eden;
use Dgharami\Eden\Facades\EdenAssets;
use Dgharami\Eden\Facades\EdenRoute;
use Dgharami\Eden\Middleware\EdenRequestHandler;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class EdenServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/eden.php', 'eden');

        $this->gate();
        $this->registerRoutes();
        $this->registerFacades();
        $this->registerStyleAndScripts();
        $this->registerPersistentMiddleware();
        $this->registerCommands();
        $this->loadViews();
        $this->loadComponents();
        $this->prepareViewComposes();
        $this->publishResources();
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
     * Assign variables via view compose
     *
     * @return void
     */
    protected function prepareViewComposes()
    {
        View::composer('eden::menu.index', function ($view) {
           return $view->with('menu', Eden::menu());
        });
        View::composer('eden::widgets.header-right', function ($view) {
           return $view->with('menu', Eden::accountMenu());
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
            __DIR__.'/Config/eden.php' => config_path('eden.php')
        ], ['config', 'eden-config']);
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

    /**
     * Register Eden routes file
     *
     * @return void
     */
    protected function registerRoutes()
    {
        Route::middleware([
            'web',
            'auth',
            config('jetstream.auth_session'),
            'verified',
            EdenRequestHandler::class,
            'can:accessEden'
        ])
            ->prefix(config('eden.entry'))
            ->group(__DIR__ . '/routes/web.php');
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
        $this->app->bind('eden', function () {
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
                MakeCard::class,
                MakeEdenPage::class
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
     * Register Eden Gate
     *
     * @return void
     */
    protected function gate(): void
    {
        Gate::define('accessEden', function ($user) {
            return true;
            return in_array($user->email, [

            ]);
        });
    }

    /**
     * Load package specific views
     *
     * @return void
     */
    protected function loadViews()
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'eden');
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
}
