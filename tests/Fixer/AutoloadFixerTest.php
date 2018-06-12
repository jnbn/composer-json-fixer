<?php

declare(strict_types = 1);

namespace Tests\Fixer;

/**
 * @covers \ComposerJsonFixer\Fixer\AutoloadFixer
 */
final class AutoloadFixerTest extends AbstractFixerTestCase
{
    public function provideFixerCases() : array
    {
        return [
            [
                [
                    'autoload' => [
                        'psr-0' => [
                            'Monolog\\'          => 'src/',
                            'Vendor_Namespace\\' => 'src/',
                        ],
                        'psr-4' => [
                            ''                    => '/',
                            'Monolog\\'           => 'src/',
                            'Vendor\\Namespace\\' => '/',
                        ],
                        'classmap' => [
                            'ClassOne.php',
                            'ClassTwo.php',
                        ],
                    ],
                ],
                [
                    'autoload' => [
                        'psr-4' => [
                            'Vendor\\Namespace\\' => '',
                            ''                    => '',
                            'Monolog\\'           => 'src/',
                        ],
                        'classmap' => [
                            'ClassTwo.php',
                            'ClassOne.php',
                        ],
                        'psr-0' => [
                            'Monolog\\'        => 'src/',
                            'Vendor_Namespace' => 'src',
                        ],
                    ],
                ],
            ],
            [
                [
                    'config' => [
                        'c' => true,
                        'a' => true,
                        'b' => true,
                    ],
                ],
            ],
        ];
    }
}
