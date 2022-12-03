<?php

namespace Dgharami\Eden\Console;

use Dgharami\Eden\Traits\StubPublisher;
use Illuminate\Console\Command;

class MakeListMetric extends Command
{
    use StubPublisher;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eden:list-card
                            {name : The Name of the Card}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new List Metric';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        $this->call('eden:card', ['name' => $name, '--type' => 'ListMetric']);

        $this->output->newLine();
        return 0;
    }

}
