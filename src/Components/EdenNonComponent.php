<?php

namespace Dgharami\Eden\Components;

use Dgharami\Eden\Components\DataTable\Actions\Action;
use Dgharami\Eden\Traits\InteractsWithEdenRoute;

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
