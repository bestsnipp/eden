<?php

namespace BestSnipp\Eden\Traits;
use BestSnipp\Eden\Components\DataTable\Actions\Action;
use Illuminate\Contracts\Queue\ShouldQueue;

trait InteractsWithAction
{

    public function applyAction($actionID, $recordID = null, $isBulkAction = false)
    {
        $recordIDs = collect($recordID)->transform(function ($value) {
            return base64_decode($value);
        })->unique()->all();

        $action = collect($this->actions)->first(function ($action) use ($actionID) {
            return $action->getKey() == $actionID;
        });

        // If action not required any type of confirmation, execute it with blank data
        $this->executeAction($action, $recordIDs, [], $isBulkAction);
    }

    protected function executeAction(Action $action, $records, $payload = [], $isBulkAction = false)
    {
        $allRecords = app($this->model())->whereIn(app($this->model())->getKeyName(), $records)->get();
        $action->setOwner($this);
        $action->prepare($allRecords, $payload, $isBulkAction);

        if ($action instanceof ShouldQueue) { // Queue the Action
            dispatch($action);
        } else { // Normal Action
            $action->handle();
        }
    }

}
