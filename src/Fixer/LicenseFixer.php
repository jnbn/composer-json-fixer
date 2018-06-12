<?php

declare(strict_types = 1);

namespace ComposerJsonFixer\Fixer;

final class LicenseFixer implements Fixer
{
    /**
     * {@inheritdoc}
     */
    public function description()
    {
        return 'adds `license` if it is missing';
    }

    /**
     * {@inheritdoc}
     */
    public function fix(array $composerJson)
    {
        if (!\array_key_exists('license', $composerJson)) {
            $composerJson['license'] = 'proprietary';
        }

        return $composerJson;
    }

    /**
     * {@inheritdoc}
     */
    public function priority()
    {
        return 0;
    }
}
