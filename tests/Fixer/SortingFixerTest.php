<?php

namespace Tests\Fixer;

/**
 * @covers \ComposerJsonFixer\Fixer\SortingFixer
 */
final class SortingFixerTest extends AbstractFixerTestCase
{
    public function provideFixerCases()
    {
        return [
            [
                [
                    'keywords' => [
                        'a',
                        'b',
                        'c',
                    ],
                ],
                [
                    'keywords' => [
                        'b',
                        'c',
                        'a',
                    ],
                ],
            ],
            [
                [
                    'not-keywords' => [
                        'b',
                        'c',
                        'a',
                    ],
                ],
            ],
        ];
    }
}
