<?php

namespace ComposerJsonFixer\Fixer;

final class NameFixer implements Fixer
{
    /**
     * {@inheritdoc}
     */
    public function description()
    {
        return 'makes package name lowercase';
    }

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

    /**
     * {@inheritdoc}
     */
    public function priority()
    {
        return 0;
    }
}
