<?php

namespace ComposerJsonFixer\Fixer;

class LowercaseFixer implements PropertyFixer
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
