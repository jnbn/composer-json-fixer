<?php

namespace ComposerJsonFixer\Fixer;

class UnwantedPropertyFixer implements DeprecatedFixer
{
    public function isCandidate($property)
    {
        return $property === 'version';
    }

    public function applyFix(&$value)
    {
        $value = null;
    }
}
