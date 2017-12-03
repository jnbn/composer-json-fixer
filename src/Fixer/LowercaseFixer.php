<?php

namespace ComposerJsonFixer\Fixer;

final class LowercaseFixer implements DeprecatedFixer
{
    public function isCandidate($property)
    {
        return $property === 'name';
    }

    public function applyFix(&$value)
    {
        $value = mb_strtolower($value);
    }
}
