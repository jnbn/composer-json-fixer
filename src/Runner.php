<?php

namespace ComposerJsonFixer;

class Runner
{
    /** @var ComposerWrapper */
    private $composerWrapper;

    /** @var JsonFile */
    private $jsonFile;

    /** @var Updater */
    private $updater;

    public function __construct(ComposerWrapper $composerWrapper, JsonFile $jsonFile, Updater $updater)
    {
        $this->composerWrapper = $composerWrapper;
        $this->jsonFile        = $jsonFile;
        $this->updater         = $updater;
    }

    public function fix()
    {
        $this->composerWrapper->callValidate();

        $fixerFactory = new FixerFactory();
        $properties   = $this->jsonFile->data();

        foreach ($fixerFactory->fixers() as $fixer) {
            $properties = $fixer->fix($properties);
        }

        $this->jsonFile->update($properties);
    }

    /**
     * @return bool
     */
    public function hasAnythingBeenFixed()
    {
        return $this->jsonFile->isModified();
    }

    /**
     * @return string
     */
    public function diff()
    {
        return $this->jsonFile->diff();
    }

    public function runUpdates()
    {
        $this->updater->update();
    }

    public function save()
    {
        $this->jsonFile->save();
    }
}
