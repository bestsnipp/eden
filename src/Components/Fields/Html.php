<?php

namespace Dgharami\Eden\Components\Fields;

class Html extends Field
{

    protected $usingFullSpace = false;

    /**
     * Use all space without any margin or padding
     *
     * @return $this
     */
    public function useFullSpace()
    {
        $this->usingFullSpace = true;
        return $this;
    }

    public function view()
    {
        return view('eden::fields.input.html')
            ->with('usingFullSpace', $this->usingFullSpace);
    }

}
