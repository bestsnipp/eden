<?php

namespace Dgharami\Eden\Cards;

use Dgharami\Eden\Components\Metrics\ViewMetric;

class EdenIntro extends \Dgharami\Eden\Components\Metric
{
    public $width = 'full';

    public $blankCanvas = true;

    protected $styleCard = 'relative';

    public function calculate()
    {
        return ViewMetric::make()
            ->withView('eden::cards.intro');
    }
}
