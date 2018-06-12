<?php

declare(strict_types = 1);

namespace ComposerJsonFixer\Fixer;

final class KeywordsFixer implements Fixer
{
    /**
     * {@inheritdoc}
     */
    public function description()
    {
        return 'sorts `keywords` by value';
    }

    /**
     * {@inheritdoc}
     */
    public function fix(array $composerJson)
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

    /**
     * {@inheritdoc}
     */
    public function priority()
    {
        return 0;
    }
}
