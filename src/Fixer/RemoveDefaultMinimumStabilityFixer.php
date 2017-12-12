<?php

namespace ComposerJsonFixer\Fixer;

final class RemoveDefaultMinimumStabilityFixer implements Fixer
{
    /**
     * {@inheritdoc}
     */
    public function description()
    {
        return 'removes `minimum-stability` if it has default value ("stable")';
    }

    /**
     * {@inheritdoc}
     */
    public function fix(array $composerJson)
    {
        foreach ($composerJson as $name => $value) {
            if ($name === 'minimum-stability' && $value === 'stable') {
                unset($composerJson[$name]);
                break;
            }
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
