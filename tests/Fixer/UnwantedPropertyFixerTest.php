<?php

namespace Tests\Fixer;

/**
 * @covers \ComposerJsonFixer\Fixer\UnwantedPropertyFixer
 */
final class UnwantedPropertyFixerTest extends AbstractFixerTestCase
{
    public function provideFixerCases()
    {
        return [
            [
                [
                    'name'        => 'foo/bar',
                    'description' => 'I am Groot',
                ],
                [
                    'name'        => 'foo/bar',
                    'version'     => '1.0.0',
                    'description' => 'I am Groot',
                ],
            ],
        ];
    }
}
