<?php

namespace BestSnipp\Eden\Components\DataTable\Actions;

use BestSnipp\Eden\Facades\Eden;
use BestSnipp\Eden\Modals\DeleteModal;

class DeleteAction extends Action
{
    public $title = 'Remove';

    public $icon = 'trash';

    protected $isAllowed = false;

    /**
     * Check if user allowed to remove the record or not
     *
     * @return void
     */
    public function onMount()
    {
        $this->show = Eden::isActionAuthorized('delete', collect($this->records)->first());
    }

    /**
     * Check bulk remove permission
     *
     * @return void
     */
    public function beforeApplyBulk()
    {
        $this->isAllowed = Eden::isActionAuthorized('delete', collect($this->records)->first());
        if (! $this->isAllowed) {
            $this->toastError('You are not authorized to perform the action');
        }
    }

    /**
     * Check if user allowed to remove the record or not
     *
     * @return void
     */
    public function beforeApply()
    {
        $this->isAllowed = Eden::isActionAuthorized('delete', collect($this->records)->first());
    }

    public function apply($records, $payload)
    {
        if ($this->isAllowed) {
            $this->emit('show'.DeleteModal::getName(), [
                'caller' => $this->owner->getName(),
                'model' => is_string($this->owner::$model) ? $this->owner::$model : get_class($this->owner::$model),
                'records' => $records,
            ]);
        }
    }
}
