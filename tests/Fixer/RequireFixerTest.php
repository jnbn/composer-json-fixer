<?php

declare(strict_types = 1);

namespace Tests\Fixer;

/**
 * @covers \ComposerJsonFixer\Fixer\RequireFixer
 */
final class RequireFixerTest extends AbstractFixerTestCase
{
    public function provideFixerCases() : array
    {
        return [
            [
                [
                    'require' => [
                        'php'                       => '^5.5 || ^7.0',
                        'roave/security-advisories' => 'dev-master',
                        'vendor/package-1'          => '>1.0',
                        'vendor/package-2'          => '>=1.0',
                        'vendor/package-3'          => '<1.0',
                        'vendor/package-4'          => '<=1.0',
                        'vendor/package-5'          => '!=1.0',
                        'vendor/package-6'          => '1.0, 2.0',
                        'vendor/package-7'          => '1.0 || 2.0',
                        'vendor/package-8'          => '1.0 - 2.0',
                        'vendor/package-9'          => '>=1.0 <2.0',
                        'vendor/package-10'         => '1.0',
                        'zorro'                     => 'dev-master',
                    ],
                ],
                [
                    'require' => [
                        'php'                       => '^5.5||^7.0',
                        'roave/security-advisories' => 'dev-master',
                        'vendor/package-1'          => ' >  1.0',
                        'vendor/package-2'          => ' >= 1.0',
                        'vendor/package-3'          => ' <  1.0',
                        'vendor/package-4'          => ' <= 1.0',
                        'vendor/package-5'          => ' != 1.0',
                        'vendor/package-6'          => '1.0,2.0',
                        'vendor/package-7'          => '1.0|2.0',
                        'vendor/package-8'          => '1.0 - 2.0',
                        'vendor/package-9'          => '>=1.0 < 2.0',
                        'vendor/package-10'         => '    1.0    ',
                        'zorro'                     => 'dev-master',
                    ],
                ],
            ],
            [
                [
                    'require-dev' => [
                        'php'                       => '^5.5 || ^7.0',
                        'roave/security-advisories' => 'dev-master',
                        'vendor/package-1'          => '>1.0',
                        'vendor/package-2'          => '>=1.0',
                        'vendor/package-3'          => '<1.0',
                        'vendor/package-4'          => '<=1.0',
                        'vendor/package-5'          => '!=1.0',
                        'vendor/package-6'          => '1.0, 2.0',
                        'vendor/package-7'          => '1.0 || 2.0',
                        'vendor/package-8'          => '1.0 - 2.0',
                        'vendor/package-9'          => '>=1.0 <2.0',
                        'vendor/package-10'         => '1.0',
                        'zorro'                     => 'dev-master',
                    ],
                ],
                [
                    'require-dev' => [
                        'php'                       => '^5.5||^7.0',
                        'roave/security-advisories' => 'dev-master',
                        'vendor/package-1'          => ' >  1.0',
                        'vendor/package-2'          => ' >= 1.0',
                        'vendor/package-3'          => ' <  1.0',
                        'vendor/package-4'          => ' <= 1.0',
                        'vendor/package-5'          => ' != 1.0',
                        'vendor/package-6'          => '1.0,2.0',
                        'vendor/package-7'          => '1.0|2.0',
                        'vendor/package-8'          => '1.0 - 2.0',
                        'vendor/package-9'          => '>=1.0 < 2.0',
                        'vendor/package-10'         => '    1.0    ',
                        'zorro'                     => 'dev-master',
                    ],
                ],
            ],
            [
                [
                    'non-require' => [
                        'foo/bar' => '1.0|2.0',
                    ],
                ],
            ],
        ];
    }
}
