<?php

namespace ComposerJsonFixer\Fixer;

final class ComposerKeysSortingFixer implements Fixer
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

    /**
     * {@inheritdoc}
     */
    public function description()
    {
        return \sprintf(
            'sorts properties according to [the documentation](%s)',
            'https://getcomposer.org/doc/04-schema.md'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function fix(array $composerJson)
    {
        \uksort(
            $composerJson,
            function ($x, $y) {
                return \array_search($x, self::PROPERTIES_ORDER, true)
                    - \array_search($y, self::PROPERTIES_ORDER, true);
            }
        );

        return $composerJson;
    }

    /**
     * {@inheritdoc}
     */
    public function priority()
    {
        return -1;
    }
}
