<?php

namespace Dgharami\Eden\Components\DataTable\Actions;

use App\Models\User;
use Faker\Factory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class DetailsAction extends StaticAction
{

    public $title = 'View Details';

    public $icon = 'eye';

    public $visibilityOnDetails = false;

    public function beforeApply()
    {
        $this->route = route('eden.show', [
            'resource' => $this->resource,
            'resourceId' => ($this->resourceId->id ?? $this->resourceId)
        ]);
    }

}
