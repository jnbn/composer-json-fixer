<?php

namespace ComposerJsonFixer\Fixer;

final class RequireFixer implements Fixer
{
    /**
     * {@inheritdoc}
     */
    public function fix(array $composerJson)
    {
        foreach ($composerJson as $name => $value) {
            if ($name !== 'require' && $name !== 'require-dev') {
                continue;
            }
            $composerJson[$name] = $this->map($value);
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

    private function map($value)
    {
        return \array_map(
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
