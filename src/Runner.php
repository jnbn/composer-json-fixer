<?php

namespace ComposerJsonFixer;

use ComposerJsonFixer\Fixer\Fixer;
use Symfony\Component\Finder\Finder;

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
        $properties = $this->file->data();

        foreach ($this->fixers() as $fixer) {
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

    /**
     * @return Fixer[]
     */
    private function fixers()
    {
        $fixers = [];
        foreach (Finder::create()->files()->in(__DIR__ . '/Fixer')->name('/.+Fixer.php$/') as $file) {
            $fixerClass = 'ComposerJsonFixer\\Fixer\\' . $file->getBasename('.php');
            $fixers[]   = new $fixerClass();
        }

        \usort(
            $fixers,
            function (Fixer $x, Fixer $y) {
                if ($x->priority() === $y->priority()) {
                    return \strnatcmp(\get_class($x), \get_class($y));
                }

                return $x->priority() < $y->priority() ? 1 : -1;
            }
        );

        return $fixers;
    }
}
