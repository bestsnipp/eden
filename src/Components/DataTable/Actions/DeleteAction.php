<?php

namespace BestSnipp\Eden\Components\DataTable\Actions;

use BestSnipp\Eden\Modals\DeleteModal;

class DeleteAction extends Action
{

    public $title = 'Remove';

    public $icon = 'trash';

    public function apply($records, $payload)
    {
        $this->emit('show' . DeleteModal::getName(), [
            'caller' => $this->owner->getName(),
            'model' => $this->owner::$model,
            'records' => $records
        ]);
    }

}
