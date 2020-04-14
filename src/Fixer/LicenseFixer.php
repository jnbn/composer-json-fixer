<?php

declare(strict_types=1);

namespace ComposerJsonFixer\Fixer;

final class LicenseFixer implements Fixer
{
    public function description() : string
    {
        return 'adds `license` if it is missing';
    }

    public function fix(array $composerJson) : array
    {
        if (! \array_key_exists('license', $composerJson)) {
            $composerJson['license'] = 'proprietary';
        }

        return $composerJson;
    }

    public function priority() : int
    {
        return 0;
    }
}
