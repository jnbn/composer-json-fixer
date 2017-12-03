<?php

namespace Tests\Fixer;

/**
 * @covers \ComposerJsonFixer\Fixer\SortingByKeyFixer
 */
final class SortingByKeyFixerTest extends AbstractFixerTestCase
{
    public function provideFixerCases()
    {
        return [
            [
                [
                    'config' => [
                        'a' => true,
                        'b' => true,
                        'c' => true,
                    ],
                ],
                [
                    'config' => [
                        'c' => true,
                        'a' => true,
                        'b' => true,
                    ],
                ],
            ],
            [
                [
                    'not-config' => [
                        'c' => true,
                        'a' => true,
                        'b' => true,
                    ],
                ],
            ],
        ];
    }
}
