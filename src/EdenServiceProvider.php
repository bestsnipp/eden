<?php
namespace Dgharami\Eden;

use Dgharami\Eden\Console\DeveloperCommand;
use Dgharami\Eden\Facades\Eden;
use Illuminate\Support\ServiceProvider;

class EdenServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/config/eden.php', 'eden');

        $this->registerFacades();
        $this->registerCommands();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //$this->loadViewsFrom(__DIR__ . '/resources/views', 'eden');
        //$this->loadRoutesFrom(__DIR__ . '/routes/web.php');
        //$this->loadComponents();
        $this->publishResources();


        dump(config('eden'));
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
        $this->app->bind('eden', function () {
            return new \Dgharami\Eden\EdenManager();
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
                //MakeCard::class,
            ]);
        }
    }

    /**
     * Auto Register Components from Package and App
     *
     * @return void
     * @throws \ReflectionException
     */
    /*protected function loadComponents()
    {
        // Register Package Components
        Livewire::component(TabGroup::getName(), TabGroup::class);
        Livewire::component(ResourceDataTable::getName(), ResourceDataTable::class);
        Livewire::component(ResourceCreateForm::getName(), ResourceCreateForm::class);
        Livewire::component(ResourceEditForm::getName(), ResourceEditForm::class);
        Livewire::component(ResourceRead::getName(), ResourceRead::class);

        // Auto Register Components that is inside \App\Http\Eden Folder
        Eden::registerComponents(
            dirname(__FILE__) . DIRECTORY_SEPARATOR . 'Modals',
            __NAMESPACE__. '\\',
            dirname(__FILE__) . DIRECTORY_SEPARATOR
        );
        Eden::registerComponents(app_path('Eden'));
    }*/
}
