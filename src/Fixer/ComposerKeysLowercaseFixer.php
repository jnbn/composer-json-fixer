<?php

namespace ComposerJsonFixer\Fixer;

final class ComposerKeysLowercaseFixer implements Fixer
{
    /**
     * {@inheritdoc}
     */
    public function description()
    {
        return 'changes all properties names to lower case';
    }

    /**
     * {@inheritdoc}
     */
    public function fix(array $composerJson)
    {
        return \array_change_key_case($composerJson);
    }

    /**
     * {@inheritdoc}
     */
    public function priority()
    {
        return 1;
    }
}
