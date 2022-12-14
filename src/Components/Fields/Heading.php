<?php

namespace BestSnipp\Eden\Components\Fields;

class Heading extends Field
{

    public $visibilityOnIndex = false;

    protected $textColor = 'text-slate-500 dark:text-slate-200';

    protected $background = 'bg-slate-100 dark:bg-slate-500';

    /**
     * Set Background Color
     *
     * @param $bgColor
     * @return $this
     */
    public function background($bgColor)
    {
        $this->background = appCall($bgColor);
        return $this;
    }

    /**
     * Set Text Color
     *
     * @param $textColor
     * @return $this
     */
    public function textColor($textColor)
    {
        $this->textColor = appCall($textColor);
        return $this;
    }

    public function view()
    {
        return view('eden::fields.input.heading')
            ->with([
                'textColor' => $this->textColor,
                'bgColor' => $this->background
            ]);
    }

    public function viewForRead()
    {
        parent::viewForRead();

        return view('eden::fields.view.heading')
        ->with([
            'textColor' => $this->textColor,
            'bgColor' => $this->background
        ]);
    }

}
