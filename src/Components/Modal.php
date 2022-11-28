<?php

namespace Dgharami\Eden\Components;

use App\Eden\DataTables\UsersDataTable;
use App\Eden\Modals\ModalA;
use Dgharami\Eden\RenderProviders\RenderProvider;
use Dgharami\Eden\RenderProviders\TabRenderer;
use Dgharami\Eden\Traits\HasToast;
use Dgharami\Eden\Traits\InteractsWithModal;
use Dgharami\Eden\Traits\MakeableComponent;
use Dgharami\Eden\Traits\ModalEvents;
use Dgharami\Eden\Traits\RouteAware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use \Livewire\Component;

/**
 * @method static make()
 */
abstract class Modal extends EdenComponent
{

    public $title = '';

    public $data = null;

    public $width = 'lg';

    protected $closeOnOutsideClick = false;

    protected $header = true;

    protected $footer = true;

    protected $confirmButtonText = 'Confirm';

    protected $cancelButtonText = 'Close';

    protected $style = 'relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 w-full';

    protected $footerStyle = 'bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 sm:justify-between gap-3';

    protected $confirmButtonStyle = 'relative inline-flex w-full md:w-auto justify-center rounded-md px-4 py-2 text-base font-medium text-white shadow-sm bg-primary-500 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto sm:text-sm';

    protected $cancelButtonStyle = 'mt-3 relative inline-flex w-full md:w-auto justify-center transition rounded-md bg-slate-100 px-4 py-2 text-base font-medium text-gray-700 hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm';

    protected $resetBeforeShow = false;

    protected $show = false;

    protected function getListeners()
    {
        $baseClassName = class_basename(get_called_class());
        $componentName = self::getName();

        return array_merge($this->listeners, [
            "toggle$baseClassName" => 'toggle',
            "toggle$componentName" => 'toggle',
            "show$baseClassName" => 'show',
            "show$componentName" => 'show',
            "dismiss$baseClassName" => 'dismiss',
            "dismiss$componentName" => 'dismiss'
        ]);
    }

    /**
     * @return void
     *
     * Toggle Modal Visibility
     */
    public function toggle()
    {
        if ($this->show) {
            $this->dismiss();
        } else {
            $this->show();
        }
    }

    /**
     * @return void
     *
     * Show Modal
     */
    public function show(...$params)
    {
        $this->data = $params;
        if ($this->resetBeforeShow) {
            $this->resetExcept();
        }
        $this->show = true;
    }

    /**
     * @return void
     *
     * Dismiss/Hide Modal
     */
    public function dismiss()
    {
        $this->show = false;
    }

    public function getData($key, $default = '')
    {
        if (isset($this->data[$key])) {
            return $this->data[$key];
        }
        return $default;
    }

    public function confirm()
    {

    }

    public function modalView()
    {
        // return UsersDataTable::make(); -> Eden Component
        // return '<p>Some HTML Content</p>';
        // return view('policy-new');
        return '';
    }

    protected function getModalWidth()
    {
        return [
            'none' => 'sm:max-w-none',
            'xs' => 'sm:max-w-xs',
            'sm' => 'sm:max-w-sm',
            'md' => 'sm:max-w-md',
            'lg' => 'sm:max-w-lg',
            'xl' => 'sm:max-w-xl',
            '2xl' => 'sm:max-w-2xl',
            '3xl' => 'sm:max-w-3xl',
            '4xl' => 'sm:max-w-4xl',
            '5xl' => 'sm:max-w-5xl',
            '6xl' => 'sm:max-w-6xl',
            '7xl' => 'sm:max-w-7xl',
            'full' => 'sm:max-w-full',
            'min' => 'sm:max-w-min',
            'max' => 'sm:max-w-max',
            'fit' => 'sm:max-w-fit',
            'screen-sm' => 'sm:max-w-screen-sm',
            'screen-md' => 'sm:max-w-screen-md',
            'screen-lg' => 'sm:max-w-screen-lg',
            'screen-xl' => 'sm:max-w-screen-xl',
            'screen-2xl' => 'sm:max-w-screen-2xl',
        ][$this->width] ?? 'sm:max-w-lg';
    }

    protected function renderModalView()
    {
        $view = $this->modalView();

        if(is_subclass_of($view, View::class)) {
            return $view->render();
        }

        return $view;
    }

    public function defaultViewParams()
    {
        return [
            'content' => $this->renderModalView(),
            'compWidth' => $this->getModalWidth(),
            'show' => $this->show,
            'closeOnOutsideClick' => $this->closeOnOutsideClick,
            'style' => $this->style,
            'header' => $this->header,
            'footer' => $this->footer,
            'footerStyle' => $this->footerStyle,
            'confirmButtonText' => $this->confirmButtonText,
            'cancelButtonText' => $this->cancelButtonText,
            'confirmButtonStyle' => $this->confirmButtonStyle,
            'cancelButtonStyle' => $this->cancelButtonStyle,
        ];
    }

    final public function view()
    {
        return view('eden::components.modal');
    }

}
