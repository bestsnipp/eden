<?php

namespace Dgharami\Eden\Console;

use Dgharami\Eden\Traits\StubPublisher;
use Illuminate\Console\Command;

class MakeCard extends Command
{
    use StubPublisher;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eden:card
                            {name : The Name of the Card}
                            {--type=StatisticMetric : Generate specific type of MetricValue `StatisticMetric`, `ProgressMetric`, `TrendMetric`, `SplitMetric`, `ListMetric`, `ViewMetric`}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Card';

    protected $namespace = 'App\\Eden\\Cards';

    protected $stubName = 'Card.stub';

    protected $targetDir = 'Eden/Cards';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        if ($this->publishStub($name)) {
            $this->info("Card {$this->name} created successfully");
        } else {
            $this->error("Card {$this->name} already exits");
        }

        $this->output->newLine();
        return 0;
    }

    private function metricTypes()
    {
        return [
            'StatisticMetric',
            'ProgressMetric',
            'TrendMetric',
            'SplitMetric',
            'ListMetric',
            'ViewMetric',
        ];
    }

    /**
     * Map the stub variables present in stub to its value
     *
     * Default `namespace` and `class` auto assigned but you can override that
     *
     * @return array
     */
    public function variables()
    {
        $type = $this->option('type');
        return [
            'type' => in_array($type, $this->metricTypes()) ? $type : 'StatisticMetric'
        ];
    }
}
