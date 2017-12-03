<?php

namespace ComposerJsonFixer;

use ComposerJsonFixer\Fixer\DeprecatedFixer;
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
        $properties = array_change_key_case($this->file->data());

        if (!array_key_exists('license', $properties)) {
            $properties['license'] = 'proprietary';
        }

        foreach ($this->propertyFixers() as $fixer) {
            foreach ($properties as $name => &$value) {
                if ($fixer->isCandidate($name)) {
                    $fixer->applyFix($value);
                }
            }
        }

        $properties = array_filter(
            $properties,
            function ($x) {
                return !empty($x);
            }
        );

        uksort(
            $properties,
            function ($x, $y) {
                return array_search($x, self::PROPERTIES_ORDER, true) - array_search($y, self::PROPERTIES_ORDER, true);
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
     * @return DeprecatedFixer[]
     */
    private function propertyFixers()
    {
        $fixers = [];
        foreach ((new Finder())->files()->in(__DIR__ . '/Fixer') as $file) {
            $fixerClass = 'ComposerJsonFixer\\Fixer\\' . $file->getBasename('.php');
            if (class_exists($fixerClass)) {
                $fixers[] = new $fixerClass();
            }
        }

        return $fixers;
    }
}
