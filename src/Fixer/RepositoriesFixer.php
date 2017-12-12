<?php

namespace ComposerJsonFixer\Fixer;

final class RepositoriesFixer implements Fixer
{
    const PROPERTIES_ORDER = [
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
            $value               = $this->sortRepositories($value);
            $composerJson[$name] = $this->sort($value);
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
                return !isset($repository['url']) || $repository['url'] !== 'https://packagist.org';
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
                return \strcmp($this->implode($x), $this->implode($y));
            }
        );

        return $value;
    }

    /**
     * @param array|string $value
     *
     * @return string
     */
    private function implode($value)
    {
        return \is_array($value)
            ? \implode('', \array_map(function ($x) {
                return $this->implode($x);
            }, $value))
            : $value;
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
