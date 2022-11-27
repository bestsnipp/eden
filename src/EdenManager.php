<?php

namespace Dgharami\Eden;

use App\Eden\Pages\DashboardPage;
use App\Eden\Pages\DataTableTestPage;
use App\Eden\Pages\FormCheckboxRadioPage;
use App\Eden\Pages\FormColorPasswordPage;
use App\Eden\Pages\FormDateTimePage;
use App\Eden\Pages\FormEditorCodePage;
use App\Eden\Pages\FormEmailNumberPage;
use App\Eden\Pages\FormMiscFieldsPage;
use App\Eden\Pages\FormSelectPage;
use App\Eden\Pages\FormSlugDependentPage;
use App\Eden\Pages\FormTextTextareaPage;
use Dgharami\Eden\Components\DataTable\Actions\DeleteAction;
use Dgharami\Eden\Components\DataTable\Actions\DetailsAction;
use Dgharami\Eden\Components\DataTable\Actions\EditAction;
use Dgharami\Eden\Components\DataTable\Actions\ReplicateAction;
use Dgharami\Eden\Components\EdenPage;
use Dgharami\Eden\Components\Menu\MenuGroup;
use Dgharami\Eden\Components\Menu\MenuHeader;
use Dgharami\Eden\Components\Menu\MenuItem;
use Dgharami\Eden\Facades\EdenRoute;
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
            MenuItem::make('Dashboard')->edenPage(DashboardPage::make()),

            MenuHeader::make('Links'),
            MenuGroup::make("Link Types", [
                MenuItem::make('Route - Newtab')
                    ->route('test-eden-page')
                    ->openInNewTab(),
                MenuItem::make('Route - Icon')
                    ->route('test-eden-page')
                    ->icon('adjustments-horizontal'),
                MenuItem::make('Path')
                    ->path('/test-page?via=path'),
                MenuItem::make('External Link - Icon')
                    ->external('https://www.google.com')
                    ->openInNewTab()
                    ->icon('external-link'),
                MenuItem::make(' Route')
                    ->route('test-eden-page'),
                MenuItem::make('Form - POST')
                    ->icon('chat-bubble-bottom-center-text')
                    ->external('https://810f20c74cd0b56b20ab9f26fd71e62d.m.pipedream.net')
                    ->viaForm('POST', ['name' => 'Debasish Gharami', 'age' => 28])
                    ->openInNewTab()
            ]),

            MenuHeader::make('Forms & Pages'),
            MenuGroup::make('Forms', [
                MenuItem::make('Text & Textarea')->edenPage(FormTextTextareaPage::make()),
                MenuItem::make('Select & Multi Select')->edenPage(FormSelectPage::make()),
                MenuItem::make('Checkbox and Radio')->edenPage(FormCheckboxRadioPage::make()),
                MenuItem::make('Email & Number')->edenPage(FormEmailNumberPage::make()),
                MenuItem::make('Color & Password')->edenPage(FormColorPasswordPage::make()),
                MenuItem::make('Date & Time')->edenPage(FormDateTimePage::make()),
                MenuItem::make('Editor & Code')->edenPage(FormEditorCodePage::make()),
                MenuItem::make('Slug & Dependent')->edenPage(FormSlugDependentPage::make()),
                MenuItem::make('Other Fields')->edenPage(FormMiscFieldsPage::make()),
            ])->icon('queue-list'),

            MenuGroup::make('Data Tables', [
                MenuItem::make('Testing DataTable')->edenPage(DataTableTestPage::make())
            ])->icon('table')
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
     * Global Actions for DataTable
     *
     * @return array
     */
    public function actions()
    {
        return [
            EditAction::make(),
            DetailsAction::make(),
            ReplicateAction::make(),
            DeleteAction::make(),
        ];
    }

    /**
     * Global Filters for DataTable
     *
     * @return array
     */
    public function filters()
    {
        return [

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

            // Bind LiveWire Components
            if (is_subclass_of($component, Component::class) && !(new ReflectionClass($component))->isAbstract()) {
                Livewire::component($component::getName(), $component);
            }

            // Register EdenPages
            if (is_subclass_of($component, EdenPage::class) && !(new ReflectionClass($component))->isAbstract()) {
                EdenRoute::register($component);
            }
        }
    }

}
