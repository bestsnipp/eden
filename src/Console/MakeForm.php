<?php

namespace Dgharami\Eden\Console;

use Dgharami\Eden\Traits\StubPublisher;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeForm extends Command
{
    use StubPublisher;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eden:form
                            {name : The name of the Form}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Form';

    protected $namespace = 'App\\Eden\\Forms';

    protected $stubName = 'Form.stub';

    protected $targetDir = 'Eden/Forms';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        if ($this->publishStub($name)) {
            $this->info("Form {$this->name} created successfully");
        } else {
            $this->error("Form {$this->name} already exits");
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
