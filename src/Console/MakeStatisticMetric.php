<?php

namespace Dgharami\Eden\Console;

use Dgharami\Eden\Traits\StubPublisher;
use Illuminate\Console\Command;

class MakeStatisticMetric extends Command
{
    use StubPublisher;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eden:card-stat
                            {name : The Name of the Card}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Statistic Metric';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        $this->call('eden:card', ['name' => $name, '--type' => 'StatisticMetric']);

        $this->output->newLine();
        return 0;
    }

}
