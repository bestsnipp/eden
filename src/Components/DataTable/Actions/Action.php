<?php

namespace BestSnipp\Eden\Components\DataTable\Actions;

use BestSnipp\Eden\Traits\AuthorizedToSee;
use BestSnipp\Eden\Traits\CanBeRendered;
use BestSnipp\Eden\Traits\CanManageVisibility;
use BestSnipp\Eden\Traits\Makeable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

abstract class Action
{
    use Makeable;
    use CanManageVisibility;
    use CanBeRendered;
    use AuthorizedToSee;

    public $title = '';

    protected $key = '';

    public $icon = null;

    protected $records = [];

    protected $payload = [];

    protected $isFromBulkAction = false;

    protected $owner = null;

    protected $resource = '';

    protected $resourceId = '';

    public function __construct()
    {
        if (is_null($this->title)) {
            $this->key = 'action_'.Str::lower(Str::random(8));
        } else {
            $this->key = 'action_'.Str::lower(Str::snake($this->title));
        }
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function setOwner($owner)
    {
        $this->owner = $owner;

        return $this;
    }

    public function allowBulk()
    {
        return true;
    }

    /**
     * List of Fields that needed during action callback
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }

    /**
     * Prepare field before render
     *
     * @param $records
     * @param $payload
     * @return $this
     */
    final public function prepareRender($records = [], $payload = [])
    {
        $this->records = $records;
        $this->payload = $payload;

        $this->assignResourceAndId();

        if (method_exists(static::class, 'onMount')) {
            appCall([$this, 'onMount'], [
                'records' => $this->records,
                'payload' => $this->payload,
            ]);
        }

        return $this;
    }

    /**
     * Prepare before applying the action
     *
     * @param $records
     * @param $payload
     * @param $isFromBulkAction
     * @return $this
     */
    final public function prepare($records = [], $payload = [], $isFromBulkAction = false)
    {
        $this->records = $records;
        $this->payload = $payload;
        $this->isFromBulkAction = $isFromBulkAction;

        $this->assignResourceAndId();

        if ($isFromBulkAction) {
            if (method_exists(static::class, 'beforeApplyBulk')) {
                appCall([$this, 'beforeApplyBulk'], [
                    'records' => $this->records,
                    'payload' => $this->payload,
                ]);
            }
        } else {
            if (method_exists(static::class, 'beforeApply')) {
                appCall([$this, 'beforeApply'], [
                    'records' => $this->records,
                    'payload' => $this->payload,
                ]);
            }
        }

        return $this;
    }

    /**
     * Set action resource
     *
     * @param string $resource
     * @return $this
     */
    public function setResource($resource)
    {
        $this->resource = $resource;
        return $this;
    }

    protected function assignResourceAndId()
    {
        if (! is_null($this->owner) && empty($this->resource)) {
            $this->resource = $this->owner->resource;
        }

        $firstRecord = collect($this->records)->first();
        if ($firstRecord instanceof Model && isset($firstRecord->{$firstRecord->getKeyName()})) {
            $this->resourceId = $firstRecord->{$firstRecord->getKeyName()};
        } elseif (is_object($firstRecord) && isset($firstRecord->id)) {
            $this->resourceId = $firstRecord->id;
        }
    }

    /**
     * Handle action call either from direct call or from queue
     *
     * @return void
     */
    public function handle()
    {
        appCall([$this, 'apply'], [
            'records' => $this->records,
            'payload' => $this->payload,
        ]);
    }

    public function __call(string $name, array $arguments)
    {
        if (! is_null($this->owner) && method_exists($this->owner, $name) && is_callable([$this->owner, $name])) {
            call_user_func_array([$this->owner, $name], $arguments);
        } else {
            call_user_func_array([$this, $name], $arguments);
        }
    }

    /**
     * @param  string  $message
     * @param  string  $title
     * @return void
     */
    public function toastError($message, $title = 'Error')
    {
        $this->toastMessage($message, 'error', $title);
    }

    /**
     * @param  string  $message
     * @param  string  $title
     * @return void
     */
    public function toastWarning($message, $title = 'Warning')
    {
        $this->toastMessage($message, 'warning', $title);
    }

    /**
     * @param  string  $message
     * @param  string  $title
     * @return void
     */
    public function toastSuccess($message, $title = 'Success')
    {
        $this->toastMessage($message, 'success', $title);
    }

    /**
     * @param  string  $message
     * @param  string  $title
     * @return void
     */
    public function toastNotification($message, $title = 'Notification')
    {
        $this->toastMessage($message, 'normal', $title);
    }

    /**
     * @return \Illuminate\Contracts\View\View
     */
    public function view($type, $record, $buttonStyle, $iconSize)
    {
        $id = '';
        if ($record instanceof Model) {
            $id = $record->{$record->getKeyName()};
        } else {
            $id = (is_null($record)) ? Str::ulid() : '';
        }

        $buttonStyle = $buttonStyle . ' ' . 'record_uid_' . $id;
        return view('eden::actions.dynamic', compact('type', 'record', 'buttonStyle', 'iconSize'))
            ->with('action', $this);
    }
}
