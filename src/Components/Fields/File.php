<?php

namespace Dgharami\Eden\Components\Fields;

use Illuminate\Support\Facades\Log;
use Livewire\TemporaryUploadedFile;

class File extends Field
{
    protected $storage = 'local';

    protected $path = 'public';

    protected $publicly = true;

    protected $displayValues = '';

    protected $meta = [
        'type' => 'file',
        'class' => 'opacity-0 absolute hidden'
    ];

    public function onMounted()
    {
        $this->storage = config('filesystems.default');
    }

    protected function processSingleFile($value)
    {
        try {
            if ($value instanceof TemporaryUploadedFile) {
                if ($this->publicly) {
                    return $value->storePublicly($this->path, $this->storage);
                }
                return $value->store($this->path, $this->storage);
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
        return is_array($value)
            ? $this->processMultipleFile($value)
            : $this->processSingleFile($value);
    }

    /**
     * @param \Illuminate\Config\Repository|\Illuminate\Contracts\Foundation\Application|mixed|string $storage
     */
    public function disk($storage)
    {
        $this->storage = $storage;
        return $this;
    }

    /**
     * @param string $path
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
            'multiple' => 'multiple'
        ]);
        return $this;
    }

    protected function prepareDisplayValues()
    {
        $this->displayValues = $this->value;
    }

    public function view()
    {
        $this->prepareDisplayValues();

        return view('eden::fields.input.file')
            ->with([
                'displayValues' => $this->displayValues
            ]);
    }

}
