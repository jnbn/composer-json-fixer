<?php

namespace Tests\Fixer;

/**
 * @covers \ComposerJsonFixer\Fixer\RepositoriesFixer
 */
final class RepositoriesFixerTest extends AbstractFixerTestCase
{
    public function provideFixerCases()
    {
        return [
            [
                [
                    'repositories' => [
                        [
                            'type' => 'vcs',
                            'url'  => 'www.example.com/repo1',
                        ],
                        [
                            'type' => 'vcs',
                            'url'  => 'www.example.com/repo2',
                        ],
                    ],
                ],
                [
                    'repositories' => [
                        [
                            'type' => 'composer',
                            'url'  => 'https://packagist.org',
                        ],
                        [
                            'url'  => 'www.example.com/repo2',
                            'type' => 'vcs',
                        ],
                        [
                            'type' => 'vcs',
                            'url'  => 'www.example.com/repo1',
                        ],
                    ],
                ],
            ],
            [
                [
                    'not-repositories' => [
                        [
                            'type' => 'composer',
                            'url'  => 'https://packagist.org',
                        ],
                    ],
                ],
            ],
        ];
    }
}
