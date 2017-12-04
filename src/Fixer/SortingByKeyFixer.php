<?php

namespace ComposerJsonFixer\Fixer;

final class SortingByKeyFixer implements Fixer
{
    /**
     * {@inheritdoc}
     */
    public function fix(array $composerJson)
    {
        foreach ($composerJson as $name => &$value) {
            if ($name !== 'config') {
                continue;
            }
            \ksort($value);
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
