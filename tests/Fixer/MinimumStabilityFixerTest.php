<?php

declare(strict_types = 1);

namespace Tests\Fixer;

/**
 * @covers \ComposerJsonFixer\Fixer\MinimumStabilityFixer
 */
final class MinimumStabilityFixerTest extends AbstractFixerTestCase
{
    public function provideFixerCases()
    {
        return [
            [
                [],
                [
                    'minimum-stability' => 'stable',
                ],
            ],
            [
                [
                    'minimum-stability' => 'dev',
                ],
            ],
        ];
    }
}
