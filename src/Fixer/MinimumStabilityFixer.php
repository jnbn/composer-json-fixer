<?php

declare(strict_types = 1);

namespace ComposerJsonFixer\Fixer;

final class MinimumStabilityFixer implements Fixer
{
    public function description() : string
    {
        return 'removes `minimum-stability` if it has default value ("stable")';
    }

    public function fix(array $composerJson) : array
    {
        foreach ($composerJson as $name => $value) {
            if ($name === 'minimum-stability' && $value === 'stable') {
                unset($composerJson[$name]);
                break;
            }
        }

        return $composerJson;
    }

    public function priority() : int
    {
        return 0;
    }
}
