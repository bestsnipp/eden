<?php

namespace BestSnipp\Eden\Console;

use BestSnipp\Eden\Traits\StubPublisher;
use Illuminate\Console\Command;

class MakeRead extends Command
{
    use StubPublisher;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eden:read
                            {name : The name of the Read}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new EdenPage';

    protected $namespace = 'App\\Eden\\Read';

    protected $stubName = 'Read.stub';

    protected $targetDir = 'Eden/Read';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        if ($this->publishStub($name)) {
            $this->info("Read {$this->name} created successfully");
        } else {
            $this->error("Read {$this->name} already exits");
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
