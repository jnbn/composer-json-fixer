<?php

declare(strict_types = 1);

namespace ComposerJsonFixer\Fixer;

final class ConfigFixer implements Fixer
{
    /**
     * {@inheritdoc}
     */
    public function description()
    {
        return 'sorts `config` by key';
    }

    /**
     * {@inheritdoc}
     */
    public function fix(array $composerJson)
    {
        foreach ($composerJson as $name => &$value) {
            if ($name !== 'config') {
                continue;
            }
            \ksort($value);
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
