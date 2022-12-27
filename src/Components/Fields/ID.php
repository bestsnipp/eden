<?php

namespace BestSnipp\Eden\Components\Fields;

/**
 * @method static static make(mixed $name = 'ID', string $key = 'id')
 */
class ID extends Hidden
{
    public $visibilityOnUpdate = false;

    public $visibilityOnCreate = false;

    public $visibilityOnIndex = true;

    protected function __construct($title = null, $key = null)
    {
        if (is_null($title)) {
            $title = 'ID';
        }
        if (is_null($key)) {
            $key = 'id';
        }
        parent::__construct($title, $key);
    }

    protected function onMount()
    {
        $this->meta = array_merge($this->meta, [
            'type' => 'hidden',
        ]);
    }

    public function view()
    {
        return '';
    }
}
