<?php

declare(strict_types = 1);

namespace ComposerJsonFixer\Fixer;

interface Fixer
{
    /**
     * Few words what fixer does.
     */
    public function description() : string;

    /**
     * Applies fix and returns fixed array of composer.json.
     */
    public function fix(array $composerJson) : array;

    /**
     * Returns the priority of the fixer.
     *
     * The default priority is 0 and higher priorities are executed first.
     */
    public function priority() : int;
}
