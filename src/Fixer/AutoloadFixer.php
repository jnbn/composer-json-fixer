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

    /**
     * {@inheritdoc}
     */
    public function description()
    {
        return 'fixes paths and sorts `autoload` and `autoload-dev`';
    }

    /**
     * {@inheritdoc}
     */
    public function fix(array $composerJson)
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

    /**
     * {@inheritdoc}
     */
    public function priority()
    {
        return 0;
    }

    /**
     * @param array $array
     *
     * @return array
     */
    private function sort(array $array)
    {
        \uksort(
            $array,
            static function ($x, $y) {
                return \array_search($x, self::PROPERTIES_ORDER, true)
                    - \array_search($y, self::PROPERTIES_ORDER, true);
            }
        );

        return $array;
    }

    /**
     * @param array $array
     *
     * @return bool
     */
    private function isArrayAssociative(array $array)
    {
        return \count(\array_filter(\array_keys($array), 'is_string')) > 0;
    }

    /**
     * @param array $autoloads
     *
     * @return array
     */
    private function fixAssociativeArray(array $autoloads)
    {
        $fixedAutoloads = [];
        foreach ($autoloads as $namespace => $directory) {
            if ($namespace !== '') {
                $namespace = (\rtrim($namespace, '\\') . '\\');
            }
            $fixedAutoloads[$namespace] = (\rtrim($directory, '/') . '/');
        }
        \ksort($fixedAutoloads);

        return $fixedAutoloads;
    }

    /**
     * @param array $autoloads
     *
     * @return array
     */
    private function fixIndexedArray(array $autoloads)
    {
        \sort($autoloads);

        return $autoloads;
    }
}
