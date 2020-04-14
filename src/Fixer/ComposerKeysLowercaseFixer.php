<?php

declare(strict_types=1);

namespace ComposerJsonFixer\Fixer;

final class ComposerKeysLowercaseFixer implements Fixer
{
    public function description() : string
    {
        return 'changes names of properties to lowercase';
    }

    public function fix(array $composerJson) : array
    {
        return \array_change_key_case($composerJson);
    }

    public function priority() : int
    {
        return 1;
    }
}
