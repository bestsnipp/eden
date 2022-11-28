<?php
namespace Dgharami\Eden;

use App\Models\User;
use Dgharami\Eden\Console\DeveloperCommand;
use Dgharami\Eden\Console\MakeCard;
use Dgharami\Eden\Console\MakeEdenPage;
use Dgharami\Eden\Facades\Eden;
use Dgharami\Eden\Facades\EdenRoute;
use Dgharami\Eden\Middleware\EdenRequestHandler;
use Illuminate\Support\Facades\Gate;
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
        $this->mergeConfigFrom(__DIR__ . '/config/eden.php', 'eden');

        $this->gate();
        $this->registerFacades();
        $this->registerPersistentMiddleware();
        $this->registerCommands();

        $this->loadViewsFrom(__DIR__ . '/resources/views', 'eden');
        $this->loadRoutesFrom(__DIR__ . '/routes/web.php');
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
            __DIR__.'/config/eden.php' => config_path('eden.php')
        ], ['config', 'eden-config']);
    }

    /**
     * Register Eden Facades
     *
     * @return void
     */
    protected function registerFacades()
    {
        $this->app->singleton('routeManager', function () {
            return new \Dgharami\Eden\RouteManager();
        });
        $this->app->singleton('modalManager', function () {
            return new \Dgharami\Eden\ModalManager();
        });
        $this->app->bind('eden', function () {
            return new \Dgharami\Eden\EdenManager();
        });
        $this->app->singleton('iconManager', function () {
            return new \Dgharami\Eden\IconManager();
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
     * Auto Register Components from Package and App
     *
     * @return void
     * @throws \ReflectionException
     */
    protected function loadComponents()
    {
        // Register Package Components
        //Livewire::component(TabGroup::getName(), TabGroup::class);
        //Livewire::component(ResourceDataTable::getName(), ResourceDataTable::class);
        //Livewire::component(ResourceCreateForm::getName(), ResourceCreateForm::class);
        //Livewire::component(ResourceEditForm::getName(), ResourceEditForm::class);
        //Livewire::component(ResourceRead::getName(), ResourceRead::class);

        // Auto Register Components that is inside \App\Http\Eden Folder
//        Eden::registerComponents(
//            dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Modals',
//            __NAMESPACE__. '\\',
//            dirname(__FILE__) . DIRECTORY_SEPARATOR
//        );
        Eden::registerComponents(app_path('Eden'));
    }
}
