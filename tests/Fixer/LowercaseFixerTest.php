<?php

namespace Tests\Fixer;

/**
 * @covers \ComposerJsonFixer\Fixer\LowercaseFixer
 */
final class LowercaseFixerTest extends AbstractFixerTestCase
{
    public function provideFixerCases()
    {
        return [
            [
                [
                    'name' => 'foo/bar',
                ],
                [
                    'name' => 'Foo/Bar',
                ],
            ],
            [
                [
                    'description' => 'Foo',
                ],
            ],
        ];
    }
}
