<?php

declare(strict_types=1);

namespace ComposerJsonFixer\Fixer;

final class ComposerKeysSortingFixer implements Fixer
{
    private const PROPERTIES_ORDER = [
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

    public function description() : string
    {
        return \sprintf(
            'sorts properties according to [the documentation](%s)',
            'https://getcomposer.org/doc/04-schema.md'
        );
    }

    public function fix(array $composerJson) : array
    {
        \uksort(
            $composerJson,
            static function (string $x, string $y) : int {
                return (int) \array_search($x, self::PROPERTIES_ORDER, true)
                    - (int) \array_search($y, self::PROPERTIES_ORDER, true);
            }
        );

        return $composerJson;
    }

    public function priority() : int
    {
        return -1;
    }
}
