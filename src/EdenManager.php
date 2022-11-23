<?php

namespace Dgharami\Eden;

use Dgharami\Eden\Components\Menu\MenuGroup;
use Dgharami\Eden\Components\Menu\MenuHeader;
use Dgharami\Eden\Components\Menu\MenuItem;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Livewire;
use ReflectionClass;
use Symfony\Component\Finder\Finder;

class EdenManager
{
    /**
     * @return array
     */
    public static function menu()
    {
        return [
            MenuHeader::make('Main'),
            MenuGroup::make("Blog", [
                MenuItem::make('Route Link ->')
                    ->route('test-eden-page')
                    ->openInNewTab(),
                MenuItem::make('Route Link ->')
                    ->route('test-eden-page')
                    ->icon('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6 13.5V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m12-3V3.75m0 9.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 3.75V16.5m-6-9V3.75m0 3.75a1.5 1.5 0 010 3m0-3a1.5 1.5 0 000 3m0 9.75V10.5" /></svg>'),
                MenuItem::make('Path Link')
                    ->path('/test-page?via=path'),
                MenuItem::make('External Link')
                    ->external('https://www.google.com')
                    ->openInNewTab()
                    ->icon('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>'),
            ]),

            MenuHeader::make('Utilities'),
            MenuItem::make('Route Link')
                ->route('test-eden-page'),
            MenuItem::make('Form Link')
                ->icon('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 01.865-.501 48.172 48.172 0 003.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" /></svg>')
                ->external('https://810f20c74cd0b56b20ab9f26fd71e62d.m.pipedream.net')
                ->viaForm('POST', ['name' => 'Debasish Gharami', 'age' => 28])
                ->openInNewTab(),
        ];
    }

    /**
     * @return array
     */
    public static function accountMenu()
    {
        return [
            MenuHeader::make('Account Info'),
            MenuItem::make('My Profile')->noIcon(),
            MenuItem::make('Account Settings')->noIcon(),

            MenuItem::make("Logout")
                ->route('logout')
                ->viaForm()
                ->noIcon(),
        ];
    }

    /**
     * @param string $directory
     * @return void
     * @throws \ReflectionException
     */
    public function registerComponents($directory, $namespace = null, $basePath = null)
    {
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

            if (is_subclass_of($component, Component::class) && !(new ReflectionClass($component))->isAbstract()) {
                Livewire::component($component::getName(), $component);
            }
        }
    }

}
