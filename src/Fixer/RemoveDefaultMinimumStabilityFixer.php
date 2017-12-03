<?php

namespace ComposerJsonFixer\Fixer;

class RemoveDefaultMinimumStabilityFixer implements DeprecatedFixer
{
    public function isCandidate($property)
    {
        return $property === 'minimum-stability';
    }

    public function applyFix(&$value)
    {
        if ($value === 'stable') {
            $value = null;
        }
    }
}
