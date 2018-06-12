<?php

declare(strict_types = 1);

namespace Tests\Fixer;

/**
 * @covers \ComposerJsonFixer\Fixer\LicenseFixer
 */
final class LicenseFixerTest extends AbstractFixerTestCase
{
    public function provideFixerCases() : array
    {
        return [
            [
                [
                    'license' => 'proprietary',
                ],
                [
                ],
            ],
            [
                [
                    'license' => 'MIT',
                ],
            ],
        ];
    }
}
