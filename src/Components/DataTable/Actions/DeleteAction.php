<?php

namespace BestSnipp\Eden\Components\DataTable\Actions;

use BestSnipp\Eden\Facades\Eden;
use BestSnipp\Eden\Modals\DeleteModal;

class DeleteAction extends Action
{

    public $title = 'Remove';

    public $icon = 'trash';

    public function __construct()
    {
        parent::__construct();
        // TODO: Needs Checking as during construction of this action models are not provided,
        // its possible to provide for single record but will create issue for multiple records
       // $this->show = Eden::isActionAuthorized('delete', collect($this->records)->first());
    }

    public function apply($records, $payload)
    {
        $this->emit('show' . DeleteModal::getName(), [
            'caller' => $this->owner->getName(),
            'model' => $this->owner::$model,
            'records' => $records
        ]);
    }

}
