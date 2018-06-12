<?php

declare(strict_types = 1);

namespace Tests;

use ComposerJsonFixer\FixerFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ComposerJsonFixer\FixerFactory
 */
final class FixerFactoryTest extends TestCase
{
    public function testFixersAreSortedByPriorityDescending() : void
    {
        $fixerFactory = new FixerFactory();

        $previousPriority = null;

        foreach ($fixerFactory->fixers() as $fixer) {
            if ($previousPriority === null) {
                $previousPriority = $fixer->priority();
                continue;
            }

            static::assertLessThanOrEqual($previousPriority, $fixer->priority());

            $previousPriority = $fixer->priority();
        }
    }
}
