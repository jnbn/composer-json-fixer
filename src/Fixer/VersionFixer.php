<?php

declare(strict_types = 1);

namespace ComposerJsonFixer\Fixer;

final class VersionFixer implements Fixer
{
    public function description() : string
    {
        return 'removes `version` if it is present';
    }

    public function fix(array $composerJson) : array
    {
        foreach ($composerJson as $name => $value) {
            if ($name !== 'version') {
                continue;
            }
            unset($composerJson[$name]);
        }

        return $composerJson;
    }

    public function priority() : int
    {
        return 0;
    }
}
