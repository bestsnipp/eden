<?php

namespace BestSnipp\Eden\Components\Fields;

use Illuminate\Support\Arr;

class Status extends Field
{
    public $visibilityOnCreate = false;

    public $visibilityOnUpdate = false;

    protected $loadingStates = [];

    protected $failedStates = [];

    protected $successIcon = 'check-circle';

    protected $loadingIcon = '<svg class="mx-auto block ml-1" viewBox="0 0 120 30" xmlns="http://www.w3.org/2000/svg" fill="currentColor" style="width: 20px;"><circle cx="15" cy="15" r="15"><animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate><animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate></circle><circle cx="60" cy="15" r="9" fill-opacity="0.3"><animate attributeName="r" from="9" to="9" begin="0s" dur="0.8s" values="9;15;9" calcMode="linear" repeatCount="indefinite"></animate><animate attributeName="fill-opacity" from="0.5" to="0.5" begin="0s" dur="0.8s" values=".5;1;.5" calcMode="linear" repeatCount="indefinite"></animate></circle><circle cx="105" cy="15" r="15"><animate attributeName="r" from="15" to="15" begin="0s" dur="0.8s" values="15;9;15" calcMode="linear" repeatCount="indefinite"></animate><animate attributeName="fill-opacity" from="1" to="1" begin="0s" dur="0.8s" values="1;.5;1" calcMode="linear" repeatCount="indefinite"></animate></circle></svg>';

    protected $failedIcon = 'exclamation-circle';

    /**
     * Success Icon to Show
     *
     * @param string $icon
     * @return $this
     */
    public function successIcon($icon = 'check-circle')
    {
        $this->successIcon = $icon;
        return $this;
    }

    /**
     * Loading Icon to Show
     *
     * @param string $icon
     * @return $this
     */
    public function loadingIcon($icon = 'ellipsis-horizontal')
    {
        $this->loadingIcon = $icon;
        return $this;
    }

    /**
     * Failed Icon to Show
     *
     * @param string $icon
     * @return $this
     */
    public function failedIcon($icon = 'exclamation-circle')
    {
        $this->failedIcon = $icon;
        return $this;
    }

    /**
     * Set Loading Values
     *
     * @param string|array|\Closure $values
     * @return $this
     */
    public function loadingWhen($values = [])
    {
        $this->loadingStates = Arr::wrap(appCall($values));
        return $this;
    }

    /**
     * Set Failed Values
     *
     * @param string|array|\Closure $values
     * @return $this
     */
    public function failedWhen($values = [])
    {
        $this->failedStates = Arr::wrap(appCall($values));
        return $this;
    }

    protected function getStyle($type = 'success')
    {
        return [
            'failed' => 'bg-red-50 text-red-600',
            'loading' => 'bg-slate-100',
            'success' => 'bg-green-50 text-green-600',
        ][$type];
    }

    protected function getIcon($type = 'success')
    {
        return [
            'failed' => $this->failedIcon,
            'loading' => $this->loadingIcon,
            'success' => $this->successIcon,
        ][$type];
    }

    protected function getIconClass($type = 'success')
    {
        return [
            'failed' => '',
            'loading' => 'animate-pulse text-slate-700',
            'success' => '',
        ][$type];
    }

    protected function getType()
    {
        if (in_array($this->value, $this->failedStates)) {
            return 'failed';
        }
        if (in_array($this->value, $this->loadingStates)) {
            return 'loading';
        }

        return 'success';
    }

    public function view()
    {
        return '';
    }

    public function viewForIndex()
    {
        parent::viewForIndex();

        $type = $this->getType();
        return view('eden::fields.row.status')
                ->with('type', $type)
                ->with('style', $this->getStyle($type))
                ->with('iconClass', $this->getIconClass($type))
                ->with('icon', $this->getIcon($type));
    }

    public function viewForRead()
    {
        parent::viewForRead();

        $type = $this->getType();
        return view('eden::fields.view.status')
            ->with('type', $type)
            ->with('style', $this->getStyle($type))
            ->with('iconClass', $this->getIconClass($type))
            ->with('icon', $this->getIcon($type));
    }
}
