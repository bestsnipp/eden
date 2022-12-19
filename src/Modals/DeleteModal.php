<?php

namespace BestSnipp\Eden\Modals;

use BestSnipp\Eden\Components\Modal;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class DeleteModal extends Modal
{
    /**
     * Title in singular form to display in DataTable
     *
     * @var string
     */
    public $title = 'Delete';

    /**
     * Modal Maximum Width, default large [ lg ]
     *
     * none, xs, sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl, full,
     * min, max, fit, screen-sm, screen-md, screen-lg, screen-xl, screen-2xl
     *
     * @var string
     */
    public $width = 'md';

    /**
     * Confirm button Text
     *
     * @var string
     */
    protected $confirmButtonText = 'Confirm';

    /**
     * Cancel Button Text
     *
     * @var string
     */
    protected $cancelButtonText = 'Close';

    protected $confirmButtonStyle = 'relative inline-flex w-full justify-center rounded-md px-4 py-2 text-base font-medium text-white shadow-sm bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 sm:w-auto sm:text-sm';

    /**
     * View to render by the modal
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|string
     */
    public function modalView()
    {
        $model = $this->getData('model', null);
        $primaryKey = '';

        if (!is_null($model)) {
            $primaryKey = app($model)->getKeyName();
        }

        $dataToShow = collect(Arr::wrap($this->getData('records', [])))
            ->transform(function ($item) use ($primaryKey) {
                if (isset($item[$primaryKey])) {
                    return '#' . ($item[$primaryKey] ?? '-');
                }
                return '#-';
            })
            ->all();

        return '<div class="px-6 pb-6 text-slate-500 dark:text-slate-300"><p>Are you sure to remove the ' . Str::pluralStudly('record', count($dataToShow)) . ' ' . implode(', ', $dataToShow) . ' ?</p></div>';
    }

    /**
     * Remove records once confirmed
     *
     * @return mixed|void
     */
    public function confirm()
    {
        $model = $this->getData('model', null);
        if (!is_null($model)) {
            $primaryKey = app($model)->getKeyName();
            $recordsToRemove = collect(Arr::wrap($this->getData('records', [])))->pluck($primaryKey)->all();
            app($model)->whereIn($primaryKey, $recordsToRemove)->delete();
        }
        if (!empty($this->resourceId)) { // Calling from Details Page, need to go back to index page
            return $this->redirectRoute('eden.page', ['resource' => $this->resource]);
        }
        $this->emit('refresh' . $this->getData('caller'));
    }
}
