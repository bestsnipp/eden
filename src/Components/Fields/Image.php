<?php

namespace BestSnipp\Eden\Components\Fields;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Livewire\TemporaryUploadedFile;

class Image extends File
{
    protected $meta = [
        'type' => 'file',
        'class' => 'opacity-0 absolute hidden',
    ];

    protected $largePreviewEnabled = true;

    protected $fileLabelEnabled = true;

    public function disableLargePreview($should = true)
    {
        $this->largePreviewEnabled = ! appCall($should);

        return $this;
    }

    public function disableFileLabel($should = true)
    {
        $this->fileLabelEnabled = ! appCall($should);

        return $this;
    }

    public function disableFileActions()
    {
        $this->fileLabelEnabled = false;
        $this->downloadEnabled = false;
        $this->largePreviewEnabled = false;

        return $this;
    }

    protected function prepareDisplayValues()
    {
        $this->displayValues = collect(Arr::wrap($this->value))
            ->filter()
            ->transform(function ($item) {
                if ($item instanceof TemporaryUploadedFile) {
                    if ($item->isPreviewable()) {
                        return $item->temporaryUrl();
                    } else {
                        return $item->getClientOriginalName();
                    }
                } elseif (! empty($item) && Storage::exists($this->path.'/'.$item)) {
                    return asset($item);
                }

                return $item;
            })
            ->filter(function ($item) {
                return ! empty($item);
            })
            ->all();
    }

    protected function prepareFilePreviews()
    {
        return collect(Arr::wrap($this->value))
            ->filter()
            ->transform(function ($path) {
            if (filter_var($path, FILTER_VALIDATE_URL)) {
                return $path;
            }

            return empty($path) ? $path : Storage::disk($this->storage)->url($path);
        })->all();
    }

    public function view()
    {
        $this->prepareDisplayValues();

        return view('eden::fields.input.image')
            ->with([
                'displayValues' => $this->displayValues,
                'isMultiple' => $this->isMultiple(),
            ]);
    }

    public function viewForIndex()
    {
        parent::viewForIndex();

        return view('eden::fields.row.image');
    }

    public function viewForRead()
    {
        $this->value = $this->prepareFilePreviews();
        parent::viewForRead();

        return view('eden::fields.view.image')->with([
            'downloadEnabled' => $this->downloadEnabled,
            'largePreviewEnabled' => $this->largePreviewEnabled,
            'fileLabelEnabled' => $this->fileLabelEnabled,
            'isMultiple' => $this->isMultiple(),
        ]);
    }
}
