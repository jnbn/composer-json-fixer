<?php

namespace ComposerJsonFixer;

use ComposerJsonFixer\Fixer\Fixer;
use Symfony\Component\Finder\Finder;

class FixerFactory
{
    /**
     * @return Fixer[]
     */
    public function fixers()
    {
        static $fixers;

        if ($fixers === null) {
            $fixers = [];

            foreach (Finder::create()->files()->in(__DIR__ . '/Fixer')->name('/.+Fixer.php$/') as $file) {
                $fixerClass = 'ComposerJsonFixer\\Fixer\\' . $file->getBasename('.php');
                $fixers[]   = new $fixerClass();
            }

            \usort(
                $fixers,
                function (Fixer $x, Fixer $y) {
                    if ($x->priority() === $y->priority()) {
                        return \strnatcmp(\get_class($x), \get_class($y));
                    }

                    return $x->priority() < $y->priority() ? 1 : -1;
                }
            );
        }

        return $fixers;
    }
}
