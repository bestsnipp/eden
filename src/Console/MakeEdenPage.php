<?php

namespace Dgharami\Eden\Console;

use Dgharami\Eden\Traits\StubPublisher;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeEdenPage extends Command
{
    use StubPublisher;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eden:page
                            {name : The Name of the Page}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new EdenPage';

    protected $namespace = 'App\\Eden\\Pages';

    protected $stubName = 'EdenPage.stub';

    protected $targetDir = 'Eden/Pages';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        if ($this->publishStub($name)) {
            $this->info("EdenPage {$this->name} created successfully");
        } else {
            $this->error("EdenPage {$this->name} already exits");
        }

        $this->output->newLine();
        return 0;
    }

    /**
     * Map the stub variables present in stub to its value
     *
     * Default `namespace` and `class` auto assigned but you can override that
     *
     * @return array
     */
    public function variables()
    {
        return [
            'slug' => Str::slug(Str::snake($this->name))
        ];
    }
}
