<?php

namespace ComposerJsonFixer\Fixer;

final class RemoveDefaultMinimumStabilityFixer implements Fixer
{
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
}
