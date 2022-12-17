<?php

namespace BestSnipp\Eden\Components\DataTable\Actions;

use App\Models\User;
use BestSnipp\Eden\Facades\Eden;
use Faker\Factory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ReplicateAction extends StaticAction
{

    public $title = 'Replicate';

    public $icon = 'duplicate';

    public function beforeApply()
    {
        $this->show = Eden::isActionAuthorized('create', collect($this->records)->first());

        $this->route = route('eden.create', [
            'resource' => $this->resource,
            'resourceId' => ($this->resourceId->id ?? $this->resourceId),
            'replicate' => true
        ]);
    }
}
