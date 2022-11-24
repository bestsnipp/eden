<?php

namespace Dgharami\Eden\Traits;

use Illuminate\Support\Str;

trait HasToast
{
    private array $types = [
        'normal' => 'bg-slate-100 text-slate-500',
        'error' => 'bg-red-100 text-red-500',
        'warning' => 'bg-orange-100 text-orange-500',
        'success' => 'bg-green-100 text-green-500',
    ];

    /**
     * @param string $message
     * @param string $type
     * @param string $title
     * @return void
     */
    public function toastMessage($message, $type = 'normal', $title = 'Notification')
    {
        $this->dispatchBrowserEvent('toast', [
            'type' => array_key_exists('normal', $this->types) ? : 'normal',
            'title' => $title ?? 'Notification',
            'message' => $message ?? '',
            'class' => ($this->types[$type] ?? $this->types['normal']),
            'hash' => Str::snake(Str::random())
        ]);
    }

    /**
     * @param string $message
     * @param string $title
     * @return void
     */
    public function toastError($message, $title = 'Error')
    {
        $this->toastMessage($message, 'error', $title);
    }

    /**
     * @param string $message
     * @param string $title
     * @return void
     */
    public function toastWarning($message, $title = 'Warning')
    {
        $this->toastMessage($message, 'warning', $title);
    }

    /**
     * @param string $message
     * @param string $title
     * @return void
     */
    public function toastSuccess($message, $title = 'Success')
    {
        $this->toastMessage($message, 'success', $title);
    }

    /**
     * @param string $message
     * @param string $title
     * @return void
     */
    public function toastNotification($message, $title = 'Notification')
    {
        $this->toastMessage($message, 'normal', $title);
    }

}
