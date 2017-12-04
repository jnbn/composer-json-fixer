<?php

namespace Tests\Fixer;

/**
 * @covers \ComposerJsonFixer\Fixer\ComposerKeysSortingFixer
 */
final class ComposerKeysSortingFixerTest extends AbstractFixerTestCase
{
    public function provideFixerCases()
    {
        return [
            [
                [
                    'name'        => 'foo/bar',
                    'description' => 'Description',
                    'type'        => 'library',
                ],
                [
                    'type'        => 'library',
                    'description' => 'Description',
                    'name'        => 'foo/bar',
                ],
            ],
        ];
    }
}
