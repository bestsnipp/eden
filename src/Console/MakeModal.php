<?php

namespace BestSnipp\Eden\Console;

use BestSnipp\Eden\Traits\StubPublisher;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeModal extends Command
{
    use StubPublisher;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eden:modal
                            {name : The name of the Modal}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new Modal';

    protected $namespace = 'App\\Eden\\Modals';

    protected $stubName = 'Modal.stub';

    protected $targetDir = 'Eden/Modals';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        if ($this->publishStub($name)) {
            $this->info("Modal {$this->name} created successfully");
        } else {
            $this->error("Modal {$this->name} already exits");
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
        $resourceName = Str::replaceLast('-modal', '', Str::slug(Str::snake(class_basename($this->name))));

        return [
            'title' => Str::ucfirst(Str::singular($resourceName)),
        ];
    }
}
