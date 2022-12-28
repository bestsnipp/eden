<?php

namespace BestSnipp\Eden\Components\Fields;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Livewire\TemporaryUploadedFile;

class KeyValue extends Field
{
    public $visibilityOnIndex = false;

    protected $value = [];

    protected $keyLabel = 'Key';

    protected $valueLabel = 'Value';

    protected $actionText = 'Add New';

    protected $maxRecords = 999999999;

    public function onMount()
    {
        $this->meta = array_merge($this->meta, [
            'class' => 'w-full h-full border-0 focus:border-0 focus:ring-0 bg-white dark:bg-slate-500',
            'rows' => 1,
            'resize' => 'false'
        ]);
    }

    /**
     * Maximum Items Allowed
     *
     * @param int $max
     * @return $this
     */
    public function maxRecords($max = 9999999)
    {
        $this->maxRecords = $max;
        return $this;
    }

    /**
     * Label for Key
     *
     * @param $label
     * @return $this
     */
    public function keyLabel($label = 'Key')
    {
        $this->keyLabel = $label;
        return $this;
    }

    /**
     * Label for Value
     *
     * @param $label
     * @return $this
     */
    public function valueLabel($label = 'Value')
    {
        $this->valueLabel = $label;
        return $this;
    }

    /**
     * Label for Action
     *
     * @param $label
     * @return $this
     */
    public function actionText($label = 'Add New')
    {
        $this->actionText = $label;
        return $this;
    }

    public function view()
    {
        return view('eden::fields.input.key-value')
            ->with([
                'keyLabel' => $this->keyLabel,
                'valueLabel' => $this->valueLabel,
                'actionText' => $this->actionText,
                'maxRecords' => $this->maxRecords,
            ]);
    }

    public function viewForIndex()
    {
        $this->value = '';

        parent::viewForRead();
    }

    public function viewForRead()
    {
        parent::viewForRead();

        return view('eden::fields.view.key-value')
        ->with([
            'keyLabel' => $this->keyLabel,
            'valueLabel' => $this->valueLabel,
            'actionText' => $this->actionText,
            'maxRecords' => $this->maxRecords,
        ]);
    }
}
