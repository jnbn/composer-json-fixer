<?php

namespace ComposerJsonFixer\Fixer;

final class LowercaseFixer implements Fixer
{
    /**
     * {@inheritdoc}
     */
    public function fix(array $composerJson)
    {
        foreach ($composerJson as $name => $value) {
            if ($name !== 'name') {
                continue;
            }
            $composerJson[$name] = \mb_strtolower($value);
        }

        return $composerJson;
    }
}
