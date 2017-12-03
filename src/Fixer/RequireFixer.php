<?php

namespace ComposerJsonFixer\Fixer;

class RequireFixer implements Fixer
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
        return $property === 'require' || $property === 'require-dev';
    }

    public function applyFix(&$value)
    {
        $value = \array_map(
            function ($require) {
                return \trim(\preg_replace(
                    [
                        '#\s*\|\|?\s*#',
                        '#\s*,\s*#',
                        '#\s+-\s+#',
                        '#\s*(>=|>|<=|<|!=)\s*#',
                    ],
                    [
                        ' || ',
                        ', ',
                        ' - ',
                        ' $1',
                    ],
                    $require
                ));
            },
            $value
        );
    }
}
