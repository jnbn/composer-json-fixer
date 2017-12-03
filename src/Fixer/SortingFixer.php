<?php

namespace ComposerJsonFixer\Fixer;

class SortingFixer implements DeprecatedFixer
{
    public function isCandidate($property)
    {
        return $property === 'keywords';
    }

    public function applyFix(&$value)
    {
        \sort($value);
    }
}
