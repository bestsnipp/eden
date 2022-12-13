<?php

namespace BestSnipp\Eden\Components\DataTable\Actions;

use App\Models\User;
use Faker\Factory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class EditAction extends StaticAction
{

    public $title = 'Edit';

    public $icon = 'pencil-alt';

    public function beforeApply()
    {
        $this->route = route('eden.edit', [
            'resource' => $this->resource,
            'resourceId' => ($this->resourceId->id ?? $this->resourceId)
        ]);
    }
}
