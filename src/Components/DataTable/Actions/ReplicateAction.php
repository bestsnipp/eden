<?php

namespace Dgharami\Eden\Components\DataTable\Actions;

use App\Models\User;
use Faker\Factory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class ReplicateAction extends Action
{

    public $title = 'Replicate';

    public $icon = 'duplicate';

    public function allowBulk()
    {
        return false;
    }

    public function apply($records, $payload)
    {
        $record = collect($records)->first();
        if (is_null($record)) {
            throw new \Exception("Null not allowed as record");
        }
        return $this->redirectRoute('eden.create', [
            'slug' => $this->getRouteParam('slug'),
            'key' => $record->id,
            'replicate' => true
        ]);
    }

}
