<?php

namespace BestSnipp\Eden\Components\Fields;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Livewire\TemporaryUploadedFile;

/**
 * @method static static make(mixed $name = 'Avatar', string $key = 'avatar')
 */
class Avatar extends Image
{
    protected function __construct($title = null, $key = null)
    {
        if (is_null($title)) {
            $title = 'Avatar';
        }
        if (is_null($key)) {
            $key = 'avatar';
        }
        parent::__construct($title, $key);
    }

    public function multiple()
    {
        return $this;
    }

}
