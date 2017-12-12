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
    public function description()
    {
        return 'sorts `repositories`';
    }

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

    /**
     * @param array $value
     *
     * @return array
     */
    private function filter(array $value)
    {
        return \array_filter(
            $value,
            function (array $repository) {
                return $repository['url'] !== 'https://packagist.org';
            }
        );
    }

    /**
     * @param array $value
     *
     * @return array
     */
    private function sort(array $value)
    {
        \usort(
            $value,
            function (array $x, array $y) {
                return \strcmp($x['type'] . $x['url'], $y['type'] . $y['url']);
            }
        );

        return $value;
    }

    /**
     * @param array $value
     *
     * @return array
     */
    private function sortRepositories(array $value)
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
