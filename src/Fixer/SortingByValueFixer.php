<?php

namespace ComposerJsonFixer\Fixer;

final class SortingByValueFixer implements Fixer
{
    /**
     * {@inheritdoc}
     */
    public function description()
    {
        return 'sorts `keywords`';
    }

    /**
     * {@inheritdoc}
     */
    public function fix(array $composerJson)
    {
        foreach ($composerJson as $name => $value) {
            if ($name !== 'keywords') {
                continue;
            }
            \sort($value);
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
}
