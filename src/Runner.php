<?php

namespace ComposerJsonFixer;

use ComposerJsonFixer\Fixer\Fixer;
use Symfony\Component\Finder\Finder;

class Runner
{
    const PROPERTIES_ORDER = [
        'name',
        'description',
        'version',
        'type',
        'keywords',
        'homepage',
        'time',
        'license',
        'authors',
        'support',
        'require',
        'require-dev',
        'conflict',
        'replace',
        'provide',
        'suggest',
        'autoload',
        'autoload-dev',
        'include-path',
        'target-dir',
        'minimum-stability',
        'prefer-stable',
        'repositories',
        'config',
        'scripts',
        'extra',
        'bin',
        'archive',
        'non-feature-branches',
    ];

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

        \uksort(
            $properties,
            function ($x, $y) {
                return \array_search($x, self::PROPERTIES_ORDER, true)
                    - \array_search($y, self::PROPERTIES_ORDER, true);
            }
        );

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
