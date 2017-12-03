<?php

namespace Tests\Fixer;

/**
 * @covers \ComposerJsonFixer\Fixer\RemoveDefaultMinimumStabilityFixer
 */
final class RemoveDefaultMinimumStabilityFixerTest extends AbstractFixerTestCase
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
