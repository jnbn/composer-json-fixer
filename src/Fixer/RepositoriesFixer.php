<?php

declare(strict_types = 1);

namespace ComposerJsonFixer\Fixer;

final class RepositoriesFixer implements Fixer
{
    private const PROPERTIES_ORDER = [
        'type',
        'url',
        'trunk-path',
        'branches-path',
        'tags-path',
        'svn-cache-credentials',
        'vendor-alias',
        'package',
        'options',
    ];

    public function description() : string
    {
        return 'sorts `repositories`';
    }

    public function fix(array $composerJson) : array
    {
        foreach ($composerJson as $name => $value) {
            if ($name !== 'repositories') {
                continue;
            }
            $value               = $this->filter($value);
            $value               = $this->sortRepositories($value);
            $composerJson[$name] = $this->sort($value);
        }

        return $composerJson;
    }

    public function priority() : int
    {
        return 0;
    }

    private function filter(array $value) : array
    {
        return \array_filter(
            $value,
            static function (array $repository) {
                return !isset($repository['url']) || $repository['url'] !== 'https://packagist.org';
            }
        );
    }

    private function sort(array $value) : array
    {
        \usort(
            $value,
            static function (array $x, array $y) {
                return \strcmp((string) \json_encode($x), (string) \json_encode($y));
            }
        );

        return $value;
    }

    private function sortRepositories(array $value) : array
    {
        return \array_map(
            static function (array $repository) {
                \uksort(
                    $repository,
                    static function ($x, $y) {
                        return (int) \array_search($x, self::PROPERTIES_ORDER, true)
                            - (int) \array_search($y, self::PROPERTIES_ORDER, true);
                    }
                );

                return $repository;
            },
            $value
        );
    }
}
