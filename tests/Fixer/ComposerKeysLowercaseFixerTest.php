<?php

namespace Tests\Fixer;

/**
 * @covers \ComposerJsonFixer\Fixer\ComposerKeysLowercaseFixer
 */
final class ComposerKeysLowercaseFixerTest extends AbstractFixerTestCase
{
    public function provideFixerCases()
    {
        return [
            [
                [
                    'name' => 'Foo/Bar',
                ],
                [
                    'Name' => 'Foo/Bar',
                ],
            ],
        ];
    }
}
