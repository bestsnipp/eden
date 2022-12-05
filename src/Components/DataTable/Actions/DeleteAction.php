<?php

namespace Dgharami\Eden\Components\DataTable\Actions;

use Dgharami\Eden\Modals\ModalConfirmDelete;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class DeleteAction extends Action
{

    public $title = 'Remove';

    public $icon = 'trash';

    public function apply($records, $payload)
    {
//        $this->showModal(ModalConfirmDelete::getName(), [
//            'model' => is_string($this->getOwner()->model) ? $this->getOwner()->model : get_class($this->getOwner()->model),
//            'records' => $records->pluck('id')
//        ]);
        foreach ($records as $record) {
            $record->delete();
            $this->toastSuccess("Record #{$record->id} Removed");
        }
    }

}
