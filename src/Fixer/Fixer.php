<?php

namespace ComposerJsonFixer\Fixer;

interface Fixer
{
    /**
     * Applies fix and returns fixed array of composer.json.
     *
     * @param array $composerJson
     *
     * @return array
     */
    public function fix(array $composerJson);

    /**
     * Returns the priority of the fixer.
     *
     * The default priority is 0 and higher priorities are executed first.
     *
     * @return int
     */
    public function priority();
}
