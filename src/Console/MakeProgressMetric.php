<?php

namespace Dgharami\Eden\Console;

use Dgharami\Eden\Traits\StubPublisher;
use Illuminate\Console\Command;

class MakeProgressMetric extends Command
{
    use StubPublisher;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eden:card-progress
                            {name : The Name of the Card}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Progress Metric';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        $this->call('eden:card', ['name' => $name, '--type' => 'ProgressMetric']);

        $this->output->newLine();
        return 0;
    }

}
