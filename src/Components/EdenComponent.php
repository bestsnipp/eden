<?php

namespace BestSnipp\Eden\Components;

use BestSnipp\Eden\Traits\AuthorizedToSee;
use BestSnipp\Eden\Traits\CanBeRefreshed;
use BestSnipp\Eden\Traits\CanBeRendered;
use BestSnipp\Eden\Traits\HasToast;
use BestSnipp\Eden\Traits\InteractsWithEdenRoute;
use BestSnipp\Eden\Traits\MakeableComponent;

abstract class EdenComponent extends \Livewire\Component
{
    use MakeableComponent;
    use InteractsWithEdenRoute;
    use CanBeRendered;
    use CanBeRefreshed;
    use HasToast;
    use AuthorizedToSee;

    public $title = '';

    public $width = '1/4';

    public $height = 'row-1';

    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Card Width
     *
     * @return string
     */
    protected function getCardWidth()
    {
        $widths = [
            '1/4' => 'col-span-1',
            '2/4' => 'col-span-2',
            '3/4' => 'col-span-3',
            '4/4' => 'col-span-4',
            'half' => 'col-span-2',
            'full' => 'col-span-4',
        ];

        return (isset($widths[$this->width])) ? $widths[$this->width] : $widths['1/4'];
    }

    /**
     * Card Height
     *
     * @return string
     */
    protected function getCardHeight()
    {
        $heights = [
            'auto' => 'row-auto',
            'row-1' => 'row-span-1',
            'row-2' => 'row-span-2',
            'row-3' => 'row-span-3',
            'row-4' => 'row-span-4',
            'row-5' => 'col-span-5',
            'row-6' => 'col-span-6',
            'full' => 'row-span-full',
        ];

        return (isset($heights[$this->height])) ? $heights[$this->height] : $heights['auto'];
    }

    /**
     * Default View Params
     *
     * @return array
     */
    public function edenDefaultViewParams()
    {
        return [
            'title' => $this->getTitle(),
            'compWidth' => $this->getCardWidth(),
            'compHeight' => $this->getCardHeight(),
        ];
    }
}
