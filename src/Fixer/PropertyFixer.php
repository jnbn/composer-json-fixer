<?php

namespace ComposerJsonFixer\Fixer;

interface PropertyFixer
{
    /**
     * Check if fixer is a candidate for given property.
     *
     * @param string $property
     *
     * @return bool
     */
    public function isCandidate($property);

    /**
     * Apply fix for given value.
     *
     * @param array|string $value
     */
    public function applyFix(&$value);
}
