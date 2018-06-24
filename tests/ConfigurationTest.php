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

        new Configuration('dir', true, true, false);
    }

    public function testDryRunAndUpgradeDev() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('It is impossible to run with both dry run and upgrade dev');

        new Configuration('dir', true, false, true);
    }

    public function testUpgradeAndUpgradeDev() : void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('It is impossible to run with both upgrade and upgrade dev');

        new Configuration('dir', false, true, true);
    }

    /**
     * @dataProvider provideCorrectConfigurationCases
     */
    public function testCorrectConfiguration(bool $dryRun, bool $upgrade, bool $upgradeDev) : void
    {
        $configuration = new Configuration('foo', $dryRun, $upgrade, $upgradeDev);

        static::assertSame('foo', $configuration->directory());
        static::assertSame($dryRun, $configuration->dryRun());
        static::assertSame($upgrade, $configuration->upgrade());
        static::assertSame($upgradeDev, $configuration->upgradeDev());
    }

    public function provideCorrectConfigurationCases() : array
    {
        return [
            [false, false, false],
            [true, false, false],
            [false, true, false],
            [false, false, true],
        ];
    }
}
