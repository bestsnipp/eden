<?php

namespace Dgharami\Eden\Components\Fields;

use Illuminate\Support\Str;

class UID extends Hidden
{
    public $visibilityOnUpdate = false;

    protected function onMount()
    {
        $this->meta = array_merge($this->meta, [
            'type' => 'hidden'
        ]);

        $this->UUID();
    }

    /**
     * Generate UUID
     *
     * @return $this
     */
    public function UUID()
    {
        $this->value = (string) Str::uuid();
        return $this;
    }

    /**
     * Generate ULID
     *
     * @return $this
     */
    public function ULID()
    {
        $this->value = (string) Str::ulid();
        return $this;
    }
}
