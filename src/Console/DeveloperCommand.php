<?php

namespace Dgharami\Eden\Console;

use Illuminate\Console\Command;

class DeveloperCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eden:developer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Developer Command';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->alert("Running Developer Command");

        $this->info("==> DONE");
        return 0;
    }
}
