<?php

namespace ComposerJsonFixer\Fixer;

interface Fixer
{
    /**
     * Apply fix and return fixed array.
     *
     * @param array $composerJson
     *
     * @return array
     */
    public function fix(array $composerJson);
}
