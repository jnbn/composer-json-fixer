<?php

namespace ComposerJsonFixer\Fixer;

class RepositoriesFixer implements DeprecatedFixer
{
    const PROPERTIES_ORDER = [
        'type',
        'url',
        'options',
        'allow_ssl_downgrade',
        'force-lazy-providers',
    ];

    public function isCandidate($property)
    {
        return $property === 'repositories';
    }

    public function applyFix(&$value)
    {
        $value = \array_filter(
            $value,
            function (array $repository) {
                return $repository['url'] !== 'https://packagist.org';
            }
        );

        \usort(
            $value,
            function (array $x, array $y) {
                return \strcmp($x['type'] . $x['url'], $y['type'] . $y['url']);
            }
        );

        \array_walk(
            $value,
            function (array &$repository) {
                \uksort(
                    $repository,
                    function ($x, $y) {
                        return \array_search($x, self::PROPERTIES_ORDER, true)
                            - \array_search($y, self::PROPERTIES_ORDER, true);
                    }
                );
            }
        );
    }
}
