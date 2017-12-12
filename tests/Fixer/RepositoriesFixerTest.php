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
                    'repositories' => [
                        [
                            'type' => 'a',
                            'url'  => 'www.example.com/repo1',
                        ],
                        [
                            'type' => 'b',
                            'url'  => 'www.example.com/repo2',
                        ],
                    ],
                ],
                [
                    'repositories' => [
                        [
                            'url'  => 'www.example.com/repo1',
                            'type' => 'a',
                        ],
                        [
                            'type' => 'b',
                            'url'  => 'www.example.com/repo2',
                        ],
                    ],
                ],
            ],
            [
                [
                    'repositories' => [
                        [
                            'type'    => 'package',
                            'package' => [
                                'name'    => 'foo/bar',
                                'version' => '1.0.0',
                                'type'    => 'foo',
                                'dist'    => [
                                    'url'  => 'www.example.com/foo/bar.zip',
                                    'type' => 'file',
                                ],
                            ],
                        ],
                        [
                            'type' => 'vcs',
                            'url'  => 'www.example.com/repo1',
                        ],
                    ],
                ],
                [
                    'repositories' => [
                        [
                            'url'  => 'www.example.com/repo1',
                            'type' => 'vcs',
                        ],
                        [
                            'package' => [
                                'name'    => 'foo/bar',
                                'version' => '1.0.0',
                                'type'    => 'foo',
                                'dist'    => [
                                    'url'  => 'www.example.com/foo/bar.zip',
                                    'type' => 'file',
                                ],
                            ],
                            'type' => 'package',
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
