<?php

declare(strict_types = 1);

namespace ComposerJsonFixer\Fixer;

final class NameFixer implements Fixer
{
    public function description() : string
    {
        return 'makes package name lowercase';
    }

    public function fix(array $composerJson) : array
    {
        foreach ($composerJson as $name => $value) {
            if ($name !== 'name') {
                continue;
            }
            $composerJson[$name] = \mb_strtolower($value);
        }

        return $composerJson;
    }

    public function priority() : int
    {
        return 0;
    }
}
