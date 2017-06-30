<?php

namespace ComposerJsonFixer\Fixer;

class SortingByKeyFixer implements PropertyFixer
{
    public function isCandidate($property)
    {
        return $property === 'config';
    }

    public function applyFix(&$value)
    {
        ksort($value);
    }
}
