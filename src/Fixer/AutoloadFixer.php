<?php

namespace ComposerJsonFixer\Fixer;

class AutoloadFixer implements PropertyFixer
{
    const PROPERTIES_ORDER = [
        'psr-0',
        'psr-4',
        'classmap',
        'files',
        'exclude-from-classmap',
    ];

    public function isCandidate($property)
    {
        return $property === 'autoload' || $property === 'autoload-dev';
    }

    public function applyFix(&$value)
    {
        array_walk(
            $value,
            function (array &$autoloads) {
                if ($this->isArrayAssociative($autoloads)) {
                    $this->fixAssociativeArray($autoloads);
                } else {
                    sort($autoloads);
                }
            }
        );

        uksort(
            $value,
            function ($x, $y) {
                return array_search($x, self::PROPERTIES_ORDER, true)
                    - array_search($y, self::PROPERTIES_ORDER, true);
            }
        );
    }

    private function isArrayAssociative(array $array)
    {
        return count(array_filter(array_keys($array), 'is_string')) > 0;
    }

    private function fixAssociativeArray(array &$autoloads)
    {
        $fixedAutoloads = [];
        foreach ($autoloads as $namespace => $directory) {
            if ($namespace !== '') {
                $namespace = (rtrim($namespace, '\\') . '\\');
            }
            $fixedAutoloads[$namespace] = (rtrim($directory, '/') . '/');
        }
        ksort($fixedAutoloads);
        $autoloads = $fixedAutoloads;
    }
}
