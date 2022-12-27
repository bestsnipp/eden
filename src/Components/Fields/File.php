<?php

namespace BestSnipp\Eden\Components\Fields;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Livewire\TemporaryUploadedFile;

class File extends Field
{
    protected $storage = 'local';

    protected $path = 'public';

    protected $publicly = true;

    protected $displayValues = '';

    protected $downloadEnabled = true;

    protected $meta = [
        'type' => 'file',
        'class' => 'opacity-0 absolute hidden',
    ];

    public function onMount()
    {
        $this->storage = config('filesystems.default');
    }

    /**
     * @param  bool|bool|\Closure  $should
     * @return $this
     */
    public function disableDownload($should = true)
    {
        $this->downloadEnabled = ! appCall($should);

        return $this;
    }

    /**
     * Process Already LiveWire Uploaded File
     *
     * @return null|array|TemporaryUploadedFile
     */
    protected function getTemporaryUploadFile($path)
    {
        if (! is_null($path)) {
            if (is_array($path)) {
                return collect($path)->map(function ($i) {
                    return ($i instanceof TemporaryUploadedFile) ? $i : TemporaryUploadedFile::createFromLivewire($i);
                })->all();
            }

            return ($path instanceof TemporaryUploadedFile) ? $path : TemporaryUploadedFile::createFromLivewire($path);
        }

        return null;
    }

    protected function processSingleFile($value)
    {
        $file = empty($value) ? $value : $this->getTemporaryUploadFile($value);

        try {
            if ($file instanceof TemporaryUploadedFile && $file->exists()) {
                if ($this->publicly) {
                    return basename($file->storePublicly($this->path, $this->storage));
                }

                return basename($file->store($this->path, $this->storage));
            }

            return $value;
        } catch (\Exception $exception) {
            return $value;
        }
    }

    protected function processMultipleFile($values)
    {
        return collect($values)->transform(function ($value) {
            return $this->processSingleFile($value);
        })->toArray();
    }

    public function process()
    {
        $value = $this->value;

        $data = is_array($value)
            ? $this->processMultipleFile($value)
            : $this->processSingleFile($value);

        return empty($data) ? null : $data;
    }

    /**
     * @param  \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed|string  $storage
     */
    public function disk($storage)
    {
        $this->storage = $storage;

        return $this;
    }

    /**
     * @param  string  $path
     */
    public function onFolder(string $path)
    {
        $this->path = $path;

        return $this;
    }

    public function privately()
    {
        $this->path = '';
        $this->publicly = false;

        return $this;
    }

    public function isMultiple()
    {
        return isset($this->meta['multiple']);
    }

    public function multiple()
    {
        $this->value = [];
        $this->meta = array_merge($this->meta, [
            'multiple' => 'multiple',
        ]);

        return $this;
    }

    protected function prepareDisplayValues()
    {
        $this->displayValues = collect(Arr::wrap($this->value))
            ->filter()
            ->transform(function ($item) {
                if ($item instanceof TemporaryUploadedFile) {
                    return $item->getClientOriginalName();
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
            return [
                'name' => basename($path),
                'url' => asset('storage/'.$path),
            ];
        })->all();
    }

    public function view()
    {
        $this->prepareDisplayValues();

        return view('eden::fields.input.file')
            ->with([
                'displayValues' => $this->displayValues,
                'isMultiple' => $this->isMultiple(),
            ]);
    }

    public function viewForIndex()
    {
        parent::viewForIndex();

        return view('eden::fields.row.file');
    }

    public function viewForRead()
    {
        $this->value = $this->prepareFilePreviews();
        parent::viewForRead();

        return view('eden::fields.view.file')->with([
            'downloadEnabled' => $this->downloadEnabled,
        ]);
    }
}
