<?php

namespace ComposerJsonFixer;

use SebastianBergmann\Diff\Differ;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

class File
{
    /** @var string */
    private $path;

    /** @var string */
    private $originalContent;

    /** @var string */
    private $currentContent;

    /**
     * @param string $path
     *
     * @throws \Exception
     */
    public function __construct($path)
    {
        $finder = (new Finder())->files()->in($path)->depth(0)->name('composer.json');

        if ($finder->count() === 0) {
            throw new \Exception(sprintf('File "composer.json" not found in "%s"', $path));
        }

        $iterator = $finder->getIterator();
        $iterator->rewind();
        $this->path = $iterator->current()->getRealpath();

        $process = new Process(
            [
               'composer',
               'validate',
               '--no-check-all',
               '--no-check-lock',
               '--no-check-publish',
            ],
            $this->dir()
        );

        $process->run();

        if ($process->getExitCode() !== 0) {
            throw new \Exception(sprintf(
                'File "composer.json" did not pass validation: %s',
                $process->getErrorOutput()
            ));
        }

        $this->originalContent = file_get_contents($this->path);
        $this->currentContent  = $this->originalContent;
    }

    /**
     * @return string
     */
    public function dir()
    {
        return dirname($this->path);
    }

    /**
     * @return array
     */
    public function data()
    {
        return json_decode($this->currentContent, true);
    }

    /**
     * @param array $data
     */
    public function update(array $data)
    {
        $this->currentContent = json_encode(
            $data,
            JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION
        ) . "\n";
    }

    /**
     * @return bool
     */
    public function isModified()
    {
        return $this->originalContent !== $this->currentContent;
    }

    public function save()
    {
        file_put_contents($this->path, $this->currentContent);
    }

    /**
     * @return string
     */
    public function diff()
    {
        return (new Differ())->diff($this->originalContent, $this->currentContent);
    }
}
