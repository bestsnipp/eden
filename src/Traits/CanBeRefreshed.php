<?php

namespace BestSnipp\Eden\Traits;

trait CanBeRefreshed
{
    public function bootCanBeRefreshed()
    {
        $baseClassName = class_basename(get_called_class());
        $componentName = self::getName();

        $this->listeners['refresh'] = '$refresh';
        $this->listeners["refresh$baseClassName"] = '$refresh';
        $this->listeners["refresh$componentName"] = '$refresh';
    }
}
