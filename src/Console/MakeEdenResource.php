<?php

namespace Dgharami\Eden\Console;

use Dgharami\Eden\Traits\StubPublisher;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeEdenResource extends Command
{
    use StubPublisher;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eden:resource
                            {name : The Name of the Resource}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new EdenResource';

    protected $namespace = 'App\\Eden\\Resources';

    protected $stubName = 'EdenResource.stub';

    protected $targetDir = 'Eden/Resources';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        if ($this->publishStub($name)) {
            $this->info("EdenResource {$this->name} created successfully");
        } else {
            $this->error("EdenResource {$this->name} already exits");
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
        $resourceName = Str::replaceLast('-resource', '', Str::slug(Str::snake($this->name)));

        return [
            'slug' => Str::plural($resourceName),
            'title' => Str::singular($resourceName)
        ];
    }
}
