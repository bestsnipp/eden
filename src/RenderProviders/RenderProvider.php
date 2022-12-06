<?php

namespace Dgharami\Eden\RenderProviders;

use Dgharami\Eden\Traits\AuthorizedToSee;

class RenderProvider
{
    use AuthorizedToSee;
    
    public $component = null;

    public $params = [];

    /**
     * @param string $component
     * @param array $params
     */
    public function __construct($component, array $params)
    {
        $this->component = $component;
        $this->params = array_merge($this->params, $params);
    }

    /**
     * Set Card Title
     *
     * @param String|\Closure $title
     * @return $this
     */
    public function title($title = '')
    {
        $this->params['title'] = appCall($title);
        return $this;
    }

    /**
     * Set Card Width
     *
     * @param String|\Closure $width
     * @return $this
     */
    public function width($width = '')
    {
        $this->params['width'] = appCall($width);
        return $this;
    }

    /**
     * Set Card Height
     *
     * @param String|\Closure $height
     * @return $this
     */
    public function height($height = '')
    {
        $this->params['height'] = appCall($height);
        return $this;
    }

}
