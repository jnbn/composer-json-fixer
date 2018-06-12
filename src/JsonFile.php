<?php

declare(strict_types = 1);

namespace ComposerJsonFixer;

use SebastianBergmann\Diff\Differ;
use Symfony\Component\Finder\Finder;

class JsonFile
{
    /** @var string */
    private $path;

    /** @var string */
    private $originalContent;

    /** @var string */
    private $currentContent;

    /**
     * @throws \Exception
     */
    public function __construct(string $path)
    {
        $finder = Finder::create()->files()->in($path)->depth(0)->name('composer.json');

        if ($finder->count() === 0) {
            throw new \Exception(\sprintf('File "composer.json" not found in "%s"', $path));
        }

        $iterator = $finder->getIterator();
        $iterator->rewind();
        $this->path = $iterator->current()->getPathname();

        $this->originalContent = \file_get_contents($this->path);
        $this->currentContent  = $this->originalContent;

        \json_decode($this->currentContent);
        if (\json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('File "composer.json" does not contain valid JSON');
        }
    }

    public function directory() : string
    {
        return \dirname($this->path);
    }

    public function data() : array
    {
        return \json_decode($this->currentContent, true);
    }

    public function update(array $data) : void
    {
        $this->currentContent = \json_encode(
            $data,
            JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_PRESERVE_ZERO_FRACTION
        ) . "\n";
    }

    public function isModified() : bool
    {
        return $this->originalContent !== $this->currentContent;
    }

    public function save() : void
    {
        \file_put_contents($this->path, $this->currentContent);
    }

    public function diff() : string
    {
        return (new Differ())->diff($this->originalContent, $this->currentContent);
    }
}
