<?php

namespace BestSnipp\Eden\Components\DataTable\Actions;

use BestSnipp\Eden\Facades\Eden;

class DetailsAction extends StaticAction
{
    public $title = 'View Details';

    public $icon = 'eye';

    public $visibilityOnDetails = false;

    public function onMount()
    {
        $this->show = Eden::isActionAuthorized('view', collect($this->records)->first());

        $this->route = route('eden.show', [
            'resource' => $this->resource,
            'resourceId' => ($this->resourceId->id ?? $this->resourceId),
        ]);
    }
}
