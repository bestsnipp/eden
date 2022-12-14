<?php

namespace BestSnipp\Eden\Components\Fields;

class Password extends Field
{
    public $visibilityOnIndex = false;

    protected $viewable = true;

    protected function onMount()
    {
        $this->meta = array_merge($this->meta, [
            'type' => 'password'
        ]);
    }

    /**
     * Enable password reveal
     *
     * @return $this
     */
    public function enableRevealing()
    {
        $this->viewable = true;
        return $this;
    }

    /**
     * Disable password reveal
     *
     * @return $this
     */
    public function disableRevealing()
    {
        $this->viewable = false;
        return $this;
    }

    public function view()
    {
        return view('eden::fields.input.password')
            ->with('viewable', $this->viewable);
    }

    public function viewForRead()
    {
        parent::viewForRead();

        return view('eden::fields.view.password')
            ->with('viewable', $this->viewable);
    }

}
