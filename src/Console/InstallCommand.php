<?php

namespace Poowf\Otter\Console;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;

class InstallCommand extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otter:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install all of the Otter resources';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->comment('Publishing Otter Service Provider...');
        $this->callSilent('vendor:publish', ['--tag' => 'otter-provider']);

        $this->comment('Publishing Otter Assets...');
        $this->callSilent('vendor:publish', ['--tag' => 'otter-assets']);

        $this->comment('Publishing Otter Configuration...');
        $this->callSilent('vendor:publish', ['--tag' => 'otter-config']);

        $this->registerOtterServiceProvider();

        $this->info('Otter scaffolding installed successfully.');
    }

    /**
     * Register the Otter service provider in the application configuration file.
     *
     * @return void
     */
    protected function registerOtterServiceProvider()
    {
        $namespace = str_replace_last('\\', '', $this->getAppNamespace());

        $appConfig = file_get_contents(config_path('app.php'));

        if (Str::contains($appConfig, $namespace.'\\Providers\\OtterServiceProvider::class')) {
            return;
        }

        file_put_contents(config_path('app.php'), str_replace(
            "{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL,
            "{$namespace}\\Providers\EventServiceProvider::class,".PHP_EOL."        {$namespace}\Providers\OtterServiceProvider::class,".PHP_EOL,
            $appConfig
        ));

        file_put_contents(app_path('Providers/OtterServiceProvider.php'), str_replace(
            "namespace App\Providers;",
            "namespace {$namespace}\Providers;",
            file_get_contents(app_path('Providers/OtterServiceProvider.php'))
        ));
    }
}