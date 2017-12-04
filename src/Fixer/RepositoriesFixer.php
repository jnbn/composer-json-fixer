<?php

namespace ComposerJsonFixer\Fixer;

final class RepositoriesFixer implements Fixer
{
    const PROPERTIES_ORDER = [
        'type',
        'url',
        'options',
        'allow_ssl_downgrade',
        'force-lazy-providers',
    ];

    /**
     * {@inheritdoc}
     */
    public function fix(array $composerJson)
    {
        foreach ($composerJson as $name => $value) {
            if ($name !== 'repositories') {
                continue;
            }
            $value               = $this->filter($value);
            $value               = $this->sort($value);
            $composerJson[$name] = $this->sortRepositories($value);
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

    private function filter($value)
    {
        return \array_filter(
            $value,
            function (array $repository) {
                return $repository['url'] !== 'https://packagist.org';
            }
        );
    }

    private function sort($value)
    {
        \usort(
            $value,
            function (array $x, array $y) {
                return \strcmp($x['type'] . $x['url'], $y['type'] . $y['url']);
            }
        );

        return $value;
    }

    private function sortRepositories($value)
    {
        return \array_map(
            function (array $repository) {
                \uksort(
                    $repository,
                    function ($x, $y) {
                        return \array_search($x, self::PROPERTIES_ORDER, true)
                            - \array_search($y, self::PROPERTIES_ORDER, true);
                    }
                );

                return $repository;
            },
            $value
        );
    }
}
