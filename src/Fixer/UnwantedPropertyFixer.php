<?php

namespace ComposerJsonFixer\Fixer;

class UnwantedPropertyFixer implements Fixer
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
        return $property === 'version';
    }

    public function applyFix(&$value)
    {
        $value = null;
    }
}
