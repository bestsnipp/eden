<?php

namespace Dgharami\Eden\Traits;

use Illuminate\View\View;

trait CanBeRefreshed
{

    public function bootCanBeRefreshed()
    {
        $baseClassName = class_basename(get_called_class());
        $componentName = self::getName();

        $this->listeners["refresh"] = '$refresh';
        $this->listeners["refresh$baseClassName"] = '$refresh';
        $this->listeners["refresh$componentName"] = '$refresh';
    }

}
