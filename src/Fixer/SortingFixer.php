<?php

namespace ComposerJsonFixer\Fixer;

final class SortingFixer implements Fixer
{
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
