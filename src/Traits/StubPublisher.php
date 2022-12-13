<?php

namespace BestSnipp\Eden\Traits;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Pluralizer;
use Illuminate\View\View;

trait StubPublisher
{
    /**
     * Filesystem instance
     * @var Filesystem
     */
    protected $files;

    /**
     * Create a new command instance.
     * @param Filesystem $files
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Publish Stub File with Given Class Name
     *
     * @param $name
     * @return bool
     */
    protected function publishStub($name)
    {
        $this->name = $this->getSingularClassName($name);

        if (empty($this->name)) {
            return false;
        }

        $path = $this->getSourceFilePath();
        $this->makeDirectory(dirname($path));

        $contents = $this->getSourceFile();

        if (!$this->files->exists($path)) {
            $this->files->put($path, $contents);
            return true;
        }

        return false;
    }

    /**
     * Map the stub variables present in stub to its value
     *
     * @return array
     */
    protected function getStubVariables()
    {
        return array_merge([
            'namespace' => $this->namespace ?? 'App\\Eden',
            'class'     => $this->name,
        ], $this->variables());
    }

    /**
     * Return the Singular Capitalize Name
     * @param $name
     * @return string
     */
    protected function getSingularClassName($name)
    {
        return ucwords(Pluralizer::singular($name));
    }

    /**
     * Return the stub file path
     *
     * @return string
     */
    protected function getStubPath()
    {
        return __DIR__ . '/../stubs/' . $this->stubName;
    }
    /**
     * Get the stub path and the stub variables
     *
     * @return bool|mixed|string
     */
    protected function getSourceFile()
    {
        return $this->getStubContents($this->getStubPath(), $this->getStubVariables());
    }

    /**
     * Replace the stub variables(key) with the desire value
     *
     * @param $stub
     * @param array $stubVariables
     * @return bool|mixed|string
     */
    protected function getStubContents($stub , $stubVariables = [])
    {
        $contents = file_get_contents($stub);

        foreach ($stubVariables as $search => $replace)
        {
            $contents = str_replace('{{ '.$search.' }}' , $replace, $contents);
        }

        return $contents;
    }

    /**
     * Get the full path of generate class
     *
     * @return string
     */
    protected function getSourceFilePath()
    {
        return app_path($this->targetDir) .'/' . $this->name . '.php';
    }

    /**
     * Build the directory for the class if necessary.
     *
     * @param  string  $path
     * @return string
     */
    protected function makeDirectory($path)
    {
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0777, true, true);
        }

        return $path;
    }

}
