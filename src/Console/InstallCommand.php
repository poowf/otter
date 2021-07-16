<?php

namespace Poowf\Otter\Console;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class InstallCommand extends Command
{
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

        $this->info('Otter scaffolding installed successfully.');
    }
}
