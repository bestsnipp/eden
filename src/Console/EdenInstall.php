<?php

namespace BestSnipp\Eden\Console;

use BestSnipp\Eden\Traits\StubPublisher;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class EdenInstall extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'eden:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install Eden';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $isLoginExists = route('login');
            // Continue Execution
        } catch (\Exception $exception) {
            $userConsent = $this->askWithCompletion(
                'Eden required Laravel Jetstream for Authentication, do you want to install',
                ['Yes', 'No'],
                'Yes');
            if (strtolower($userConsent) == 'no') {
                $this->error('Eden required Laravel Jetstream for Authentication');
                return 1;
            }

            $this->call('jetstream:install', ['livewire']);
        }

        $this->info('Publishing Eden Service Provider...');
        $this->callSilent('vendor:publish', ['--tag' => 'eden-provider']);
        $this->installEdenServiceProvider();

        $this->info('Publishing Eden Config File...');
        $this->callSilent('vendor:publish', ['--tag' => 'eden-config']);

        $this->warn('Success ...');
        $this->output->newLine();
        return 0;
    }

    /**
     * Add the Eden service providers in the app config file.
     *
     * @return void
     */
    protected function installEdenServiceProvider()
    {
        $namespace = Str::replaceLast('\\', '', $this->laravel->getNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, "{$namespace}\\Providers\\EdenServiceProvider::class")) {
            return;
        }

        $lineEndingCount = [
            "\r\n" => substr_count($appConfig, "\r\n"),
            "\r" => substr_count($appConfig, "\r"),
            "\n" => substr_count($appConfig, "\n"),
        ];

        $eol = array_keys($lineEndingCount, max($lineEndingCount))[0];

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\EventServiceProvider::class,".$eol,
            "{$namespace}\\Providers\EventServiceProvider::class,".$eol."        {$namespace}\Providers\EdenServiceProvider::class,".$eol,
            $appConfig
        ));
    }
}
