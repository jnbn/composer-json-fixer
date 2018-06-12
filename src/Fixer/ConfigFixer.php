<?php

declare(strict_types = 1);

namespace ComposerJsonFixer\Fixer;

final class ConfigFixer implements Fixer
{
    public function description() : string
    {
        return 'sorts `config` by key';
    }

    public function fix(array $composerJson) : array
    {
        foreach ($composerJson as $name => &$value) {
            if ($name !== 'config') {
                continue;
            }
            \ksort($value);
        }

        return $composerJson;
    }

    public function priority() : int
    {
        return 0;
    }
}
