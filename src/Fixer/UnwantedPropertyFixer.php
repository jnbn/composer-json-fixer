<?php

namespace ComposerJsonFixer\Fixer;

final class UnwantedPropertyFixer implements Fixer
{
    /**
     * {@inheritdoc}
     */
    public function description()
    {
        return 'removes `version` if it is present';
    }

    /**
     * {@inheritdoc}
     */
    public function fix(array $composerJson)
    {
        foreach ($composerJson as $name => $value) {
            if ($name !== 'version') {
                continue;
            }
            unset($composerJson[$name]);
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
