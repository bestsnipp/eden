<?php

namespace BestSnipp\Eden\Cards;

use BestSnipp\Eden\Components\Metrics\ViewMetric;

class EdenIntro extends \BestSnipp\Eden\Components\Metric
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
