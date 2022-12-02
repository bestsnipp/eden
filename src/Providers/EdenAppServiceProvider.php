<?php

namespace Dgharami\Eden\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class EdenAppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->gate();
        $this->plugins();
    }

    /**
     * Register Eden Gate
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('accessEden', function ($user) {
            return true;
//            return in_array($user->email, [
//
//            ]);
        });
    }

    /**
     * Register Application Plugins
     *
     * @return array
     */
    protected function plugins()
    {

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

}
