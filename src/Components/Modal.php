<?php

namespace Dgharami\Eden\Components;

use App\Eden\DataTables\UsersDataTable;
use App\Eden\Modals\ModalA;
use Dgharami\Eden\RenderProviders\TabRenderer;
use Dgharami\Eden\Traits\HasToast;
use Dgharami\Eden\Traits\InteractsWithModal;
use Dgharami\Eden\Traits\MakeableComponent;
use Dgharami\Eden\Traits\ModalEvents;
use Dgharami\Eden\Traits\RouteAware;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use \Livewire\Component;

abstract class Modal extends EdenComponent
{

    public $title = '';

    public $show = false;

    public $closeOnOutsideClick = false;

    public $header = true;

    public $footer = true;

    public $confirmButtonText = 'Confirm';

    public $cancelButtonText = 'Close';

    public $style = 'relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full';

    public $footerStyle = 'bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 sm:justify-between';

    public $confirmButtonStyle = 'relative inline-flex w-full justify-center rounded-md px-4 py-2 text-base font-medium text-white shadow-sm bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 sm:w-auto sm:text-sm';

    public $cancelButtonStyle = 'mt-3 relative inline-flex w-full justify-center transition rounded-md bg-slate-100 px-4 py-2 text-base font-medium text-gray-700 hover:bg-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-300 focus:ring-offset-2 sm:mt-0 sm:w-auto sm:text-sm';

    public $width = 'lg';

    protected $resetBeforeShow = false;

    public $data = null;

    protected function getListeners()
    {
        return array_merge($this->listeners, [
            'toggle' => 'toggle',
            'show' => 'show',
            'dismiss' => 'dismiss'
        ]);
    }

    /**
     * @return void
     *
     * Toogle Modal Visibility
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
    public function show($params)
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

    public function view()
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

    public function render()
    {
        return view('eden::components.modal')
            ->with([
                'content' => $this->view(),
                'width' => $this->getModalWidth()
            ]);
    }

}
