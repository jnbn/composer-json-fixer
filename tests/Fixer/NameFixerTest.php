<?php

declare(strict_types = 1);

namespace Tests\Fixer;

/**
 * @covers \ComposerJsonFixer\Fixer\NameFixer
 */
final class NameFixerTest extends AbstractFixerTestCase
{
    public function provideFixerCases() : array
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
