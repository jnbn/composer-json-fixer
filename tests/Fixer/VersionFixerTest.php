<?php

declare(strict_types = 1);

namespace Tests\Fixer;

/**
 * @covers \ComposerJsonFixer\Fixer\VersionFixer
 */
final class VersionFixerTest extends AbstractFixerTestCase
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
