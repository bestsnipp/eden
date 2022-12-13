<?php

namespace BestSnipp\Eden\Components;

use BestSnipp\Eden\Components\DataTable\Actions\Action;
use BestSnipp\Eden\Traits\InteractsWithEdenRoute;

abstract class EdenNonComponent
{
    use InteractsWithEdenRoute;

    public function __construct()
    {
        $this->bootTraits();

        if (method_exists($this, 'onMount')) {
            appCall([$this, 'onMount']);
        }
    }

    protected function bootTraits()
    {
        foreach (class_uses(self::class) as $trait) {
            $method = 'boot'.class_basename($trait);

            if (method_exists($this, $method)) {
                appCall([$this, $method]);
            }
        }
    }

}
