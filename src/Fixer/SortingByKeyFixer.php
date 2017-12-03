<?php

namespace ComposerJsonFixer\Fixer;

class SortingByKeyFixer implements Fixer
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
