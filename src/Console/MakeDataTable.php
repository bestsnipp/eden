<?php

namespace BestSnipp\Eden\Console;

use BestSnipp\Eden\Traits\StubPublisher;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeDataTable extends Command
{
    use StubPublisher;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eden:datatable
                            {name : The name of the DataTable}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create new DataTable';

    protected $namespace = 'App\\Eden\\DataTables';

    protected $stubName = 'DataTable.stub';

    protected $targetDir = 'Eden/DataTables';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

        if ($this->publishStub($name)) {
            $this->info("DataTable {$this->name} created successfully");
        } else {
            $this->error("DataTable {$this->name} already exits");
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
        $resourceName = Str::replaceLast('-datatable', '', Str::slug(Str::snake(class_basename($this->name))));

        return [
            'slug' => Str::plural($resourceName),
            'title' => Str::singular($resourceName),
        ];
    }
}
