<?php

namespace ComposerJsonFixer\Fixer;

final class LicenseFixer implements Fixer
{
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
