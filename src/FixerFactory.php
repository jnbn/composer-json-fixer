<?php

declare(strict_types=1);

namespace ComposerJsonFixer;

use ComposerJsonFixer\Fixer\Fixer;
use Symfony\Component\Finder\Finder;

class FixerFactory
{
    /**
     * @return Fixer[]
     */
    public function fixers() : array
    {
        $fixers = [];

        foreach (Finder::create()->files()->in(__DIR__.'/Fixer')->name('/.+Fixer.php$/') as $file) {
            $fixerClass = 'ComposerJsonFixer\\Fixer\\'.$file->getBasename('.php');
            $fixers[] = new $fixerClass();
        }

        \usort(
            $fixers,
            static function (Fixer $x, Fixer $y) : int {
                if ($x->priority() === $y->priority()) {
                    return \strnatcmp(\get_class($x), \get_class($y));
                }

                return $x->priority() < $y->priority() ? 1 : -1;
            }
        );

        return $fixers;
    }
}
