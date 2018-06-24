<?php

declare(strict_types = 1);

namespace Tests;

use ComposerJsonFixer\Configuration;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ComposerJsonFixer\Configuration
 */
final class ConfigurationTest extends TestCase
{
    public function testDryRunAndUpgrade() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('It is impossible to run with both dry run and upgrade');

        new Configuration('dir', true, true);
    }

    /**
     * @dataProvider provideCorrectConfigurationCases
     */
    public function testCorrectConfiguration(bool $dryRun, bool $upgrade) : void
    {
        $configuration = new Configuration('foo', $dryRun, $upgrade);

        static::assertSame('foo', $configuration->directory());
        static::assertSame($dryRun, $configuration->dryRun());
        static::assertSame($upgrade, $configuration->upgrade());
    }

    public function provideCorrectConfigurationCases() : array
    {
        return [
            [false, false],
            [true, false],
            [false, true],
        ];
    }
}
