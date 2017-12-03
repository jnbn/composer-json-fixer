<?php

namespace ComposerJsonFixer\Fixer;

class SortingFixer implements Fixer
{
    /**
     * {@inheritdoc}
     */
    public function fix(array $composerJson)
    {
        foreach ($composerJson as $name => $value) {
            if (!$this->isCandidate($name)) {
                continue;
            }
            $this->applyFix($value);
            $composerJson[$name] = $value;
        }

        return $composerJson;
    }

    public function isCandidate($property)
    {
        return $property === 'keywords';
    }

    public function applyFix(&$value)
    {
        \sort($value);
    }
}
