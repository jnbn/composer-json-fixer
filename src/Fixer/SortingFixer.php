<?php

namespace ComposerJsonFixer\Fixer;

class SortingFixer implements PropertyFixer
{
    public function isCandidate($property)
    {
        return $property === 'keywords';
    }

    public function applyFix(&$value)
    {
        sort($value);
    }
}
