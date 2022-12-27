<?php

namespace BestSnipp\Eden\Components\DataTable\Actions;

use BestSnipp\Eden\Facades\Eden;

class EditAction extends StaticAction
{
    public $title = 'Edit';

    public $icon = 'pencil-alt';

    public function onMount()
    {
        $this->show = Eden::isActionAuthorized('update', collect($this->records)->first());

        $this->route = route('eden.edit', [
            'resource' => $this->resource,
            'resourceId' => ($this->resourceId->id ?? $this->resourceId),
        ]);
    }
}
