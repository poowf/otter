<?php

namespace Poowf\Otter\Console;

use Poowf\Otter\Otter;
use Illuminate\Console\GeneratorCommand;

class ResourceCommand extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'otter:resource {name : The name of the class} {--model= : The class name of the model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Otter Resource class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Resource';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/../../stubs/OtterResource.stub';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = str_replace_first($this->rootNamespace(), '', $name);

        return $this->laravel->basePath().'/app'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $model = $this->option('model');
        //Check if class name of the model is supplied and use that, otherwise use the root name space and append the name of the model for the classname
        $fullModelClassName = ($model) ? str_replace('/', '\\', $model) : $this->rootNamespace() . '\\' . $this->argument('name');
        //Replace the text with the full model classname and execute the buildclass to generate the rest of the stub
        return str_replace('DummyFullClass', $fullModelClassName, parent::buildClass($name));
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . Otter::$otterBaseNamespace;
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return 'App';
    }
}
