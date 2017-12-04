<?php

namespace Tests;

use ComposerJsonFixer\FixerFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ComposerJsonFixer\FixerFactory
 */
final class FixerFactoryTest extends TestCase
{
    public function testFixersAreSortedByPriorityDescending()
    {
        $fixerFactory = new FixerFactory();

        $previousPriority = null;

        foreach ($fixerFactory->fixers() as $fixer) {
            if ($previousPriority === null) {
                $previousPriority = $fixer->priority();
                continue;
            }

            $this->assertLessThanOrEqual($previousPriority, $fixer->priority());

            $previousPriority = $fixer->priority();
        }
    }
}
