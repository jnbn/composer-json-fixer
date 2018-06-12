<?php

declare(strict_types = 1);

namespace Tests\Fixer;

/**
 * @covers \ComposerJsonFixer\Fixer\KeywordsFixer
 */
final class KeywordsFixerTest extends AbstractFixerTestCase
{
    public function provideFixerCases() : array
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
