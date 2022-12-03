<?php

namespace Dgharami\Eden\Console;

use Dgharami\Eden\Traits\StubPublisher;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeField extends Command
{
    use StubPublisher;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eden:field
                            {name : The name of the Field}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Field';

    protected $namespace = 'App\\Eden\\Fields';

    protected $stubName = 'Field.stub';

    protected $targetDir = 'Eden/Fields';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        if ($this->publishStub($name)) {
            $this->info("Field {$this->name} created successfully");
        } else {
            $this->error("Field {$this->name} already exits");
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
