<?php

namespace BestSnipp\Eden\Components\DataTable\Actions;

use BestSnipp\Eden\Facades\Eden;

class ReplicateAction extends StaticAction
{
    public $title = 'Replicate';

    public $icon = 'duplicate';

    public function onMount()
    {
        $this->show = Eden::isActionAuthorized('create', collect($this->records)->first());

        $this->route = route('eden.create', [
            'resource' => $this->resource,
            'resourceId' => ($this->resourceId->id ?? $this->resourceId),
            'replicate' => true,
        ]);
    }
}
