<?php

namespace ComposerJsonFixer\Fixer;

final class RequireFixer implements Fixer
{
    /**
     * {@inheritdoc}
     */
    public function description()
    {
        return 'cleans up versions for `require` and `require-dev`';
    }

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

    /**
     * @param array $value
     *
     * @return array
     */
    private function map(array $value)
    {
        return \array_map(
            static function ($require) {
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
