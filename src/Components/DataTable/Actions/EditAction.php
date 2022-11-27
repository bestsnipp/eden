<?php

namespace Dgharami\Eden\Components\DataTable\Actions;

use App\Models\User;
use Faker\Factory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

class EditAction extends Action
{

    public $title = 'Edit';

    public $icon = 'pencil-alt';

    public function allowBulk()
    {
        return false;
    }

    public function apply($records, $payload = [])
    {
        $record = collect($records)->first();
        if (is_null($record)) {
            throw new \Exception("Null not allowed as record");
        }

        $this->toastSuccess('Success Toast');
        //redirect()->route('eden.page', 'dashboard');
    }

}
