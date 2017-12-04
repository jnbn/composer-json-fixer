<?php

namespace ComposerJsonFixer;

class Runner
{
    /** @var File */
    private $file;

    /**
     * @param string $path
     */
    public function __construct($path)
    {
        $this->file = new File($path);
    }

    public function fix()
    {
        $fixerFactory = new FixerFactory();
        $properties   = $this->file->data();

        foreach ($fixerFactory->fixers() as $fixer) {
            $properties = $fixer->fix($properties);
        }

        $this->file->update($properties);
    }

    public function hasAnythingBeenFixed()
    {
        return $this->file->isModified();
    }

    public function diff()
    {
        return $this->file->diff();
    }

    public function runUpdates()
    {
        $updater = new Updater($this->file);
        $updater->update();
    }

    public function save()
    {
        $this->file->save();
    }
}
