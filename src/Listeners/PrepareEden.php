<?php

namespace BestSnipp\Eden\Listeners;


use BestSnipp\Eden\Providers\EdenCoreServiceProvider;

class PrepareEden
{
    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle()
    {
        $this->registerPlugins();
    }

    /**
     * Boot the standard Nova tools.
     *
     * @return void
     */
    protected function registerPlugins()
    {
        // TODO : Implement All Registered Plugins
    }
}
