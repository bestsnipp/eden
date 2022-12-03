<?php

namespace Dgharami\Eden\Console;

use Dgharami\Eden\Traits\StubPublisher;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeFilter extends Command
{
    use StubPublisher;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eden:filter
                            {name : The name of the Filter}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Filter';

    protected $namespace = 'App\\Eden\\Filters';

    protected $stubName = 'Filter.stub';

    protected $targetDir = 'Eden/Filters';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        if ($this->publishStub($name)) {
            $this->info("Filter {$this->name} created successfully");
        } else {
            $this->error("Filter {$this->name} already exits");
        }

        $this->output->newLine();
        return 0;
    }

    /**
     * Map the stub variables present in stub to its value
     *
     * Default `namespace` and `class` auto assigned, but you can override that
     *
     * @return array
     */
    public function variables()
    {
        return [];
    }
}
