<?php

declare(strict_types = 1);

namespace ComposerJsonFixer\Fixer;

final class KeywordsFixer implements Fixer
{
    public function description() : string
    {
        return 'sorts `keywords` by value';
    }

    public function fix(array $composerJson) : array
    {
        foreach ($composerJson as $name => $value) {
            if ($name !== 'keywords') {
                continue;
            }
            \sort($value);
            $composerJson[$name] = $value;
        }

        return $composerJson;
    }

    public function priority() : int
    {
        return 0;
    }
}
