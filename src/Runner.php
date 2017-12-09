<?php

namespace ComposerJsonFixer;

class Runner
{
    /** @var JsonFile */
    private $jsonFile;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->jsonFile = new JsonFile($path);

        $composerWrapper = new ComposerWrapper();
        $composerWrapper->validate($this->jsonFile->directory());
    }

    public function fix()
    {
        $fixerFactory = new FixerFactory();
        $properties   = $this->jsonFile->data();

        foreach ($fixerFactory->fixers() as $fixer) {
            $properties = $fixer->fix($properties);
        }

        $this->jsonFile->update($properties);
    }

    public function hasAnythingBeenFixed()
    {
        return $this->jsonFile->isModified();
    }

    public function diff()
    {
        return $this->jsonFile->diff();
    }

    public function runUpdates()
    {
        $updater = new Updater($this->jsonFile);
        $updater->update();
    }

    public function save()
    {
        $this->jsonFile->save();
    }
}
