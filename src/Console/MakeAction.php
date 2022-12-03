<?php

namespace Dgharami\Eden\Console;

use Dgharami\Eden\Traits\StubPublisher;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeAction extends Command
{
    use StubPublisher;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eden:action
                            {name : The Name of the Action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Action';

    protected $namespace = 'App\\Eden\\Actions';

    protected $stubName = 'Action.stub';

    protected $targetDir = 'Eden/Actions';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        if ($this->publishStub($name)) {
            $this->info("Action {$this->name} created successfully");
        } else {
            $this->error("Action {$this->name} already exits");
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
        $resourceName = Str::replaceLast('-action', '', Str::slug(Str::snake(class_basename($this->name))));

        return [
            'title' => Str::ucfirst($resourceName)
        ];
    }
}
