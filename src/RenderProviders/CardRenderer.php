<?php

namespace BestSnipp\Eden\RenderProviders;

class CardRenderer extends RenderProvider
{
    /**
     * Set Filter Value
     *
     * @param $filter
     * @return $this
     */
    public function filter($filter = '')
    {
        $this->params['filter'] = appCall($filter);

        return $this;
    }

    /**
     * Set Card Blank Canvas
     *
     * @param  bool|\Closure  $should
     * @return $this
     */
    public function asBlankCanvas($should = true)
    {
        $this->params['blankCanvas'] = appCall($should);

        return $this;
    }
}
