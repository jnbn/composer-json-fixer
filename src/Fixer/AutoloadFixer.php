<?php

declare(strict_types = 1);

namespace ComposerJsonFixer\Fixer;

final class AutoloadFixer implements Fixer
{
    private const PROPERTIES_ORDER = [
        'psr-0',
        'psr-4',
        'classmap',
        'files',
        'exclude-from-classmap',
    ];

    public function description() : string
    {
        return 'fixes paths and sorts `autoload` and `autoload-dev`';
    }

    public function fix(array $composerJson) : array
    {
        foreach ($composerJson as $name => $value) {
            if ($name !== 'autoload' && $name !== 'autoload-dev') {
                continue;
            }
            $value = $this->sort($value);
            $value = \array_map(
                function (array $autoloads) {
                    return $this->isArrayAssociative($autoloads)
                        ? $this->fixAssociativeArray($autoloads)
                        : $this->fixIndexedArray($autoloads);
                },
                $value
            );
            $composerJson[$name] = $value;
        }

        return $composerJson;
    }

    public function priority() : int
    {
        return 0;
    }

    private function sort(array $array) : array
    {
        \uksort(
            $array,
            static function (string $x, string $y) {
                return (int) \array_search($x, self::PROPERTIES_ORDER, true)
                    - (int) \array_search($y, self::PROPERTIES_ORDER, true);
            }
        );

        return $array;
    }

    private function isArrayAssociative(array $array) : bool
    {
        return \count(\array_filter(\array_keys($array), 'is_string')) > 0;
    }

    private function fixAssociativeArray(array $autoloads) : array
    {
        $fixedAutoloads = [];
        foreach ($autoloads as $namespace => $directory) {
            if (\is_string($namespace) && $namespace !== '') {
                $namespace = (\rtrim($namespace, '\\') . '\\');
            }
            $fixedAutoloads[$namespace] = (\rtrim($directory, '/') . '/');
        }
        \ksort($fixedAutoloads);

        return $fixedAutoloads;
    }

    private function fixIndexedArray(array $autoloads) : array
    {
        \sort($autoloads);

        return $autoloads;
    }
}
