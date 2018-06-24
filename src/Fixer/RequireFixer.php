<?php

declare(strict_types = 1);

namespace ComposerJsonFixer\Fixer;

final class RequireFixer implements Fixer
{
    public function description() : string
    {
        return 'cleans up versions for `require` and `require-dev`';
    }

    public function fix(array $composerJson) : array
    {
        foreach ($composerJson as $name => $value) {
            if ($name !== 'require' && $name !== 'require-dev') {
                continue;
            }
            $composerJson[$name] = $this->map($value);
        }

        return $composerJson;
    }

    public function priority() : int
    {
        return 0;
    }

    private function map(array $value) : array
    {
        return \array_map(
            static function (string $require) {
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
