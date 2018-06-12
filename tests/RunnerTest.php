<?php

declare(strict_types = 1);

namespace Tests;

use ComposerJsonFixer\ComposerWrapper;
use ComposerJsonFixer\JsonFile;
use ComposerJsonFixer\Runner;
use ComposerJsonFixer\Updater;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ComposerJsonFixer\Runner
 */
final class RunnerTest extends TestCase
{
    private $composerWrapper;
    private $jsonFile;
    private $updater;

    protected function setUp() : void
    {
        $this->composerWrapper = $this->createMock(ComposerWrapper::class);
        $this->jsonFile        = $this->createMock(JsonFile::class);
        $this->updater         = $this->createMock(Updater::class);
    }

    public function testFixing() : void
    {
        $this->jsonFile->method('data')->willReturn([]);
        $this->jsonFile->expects(static::once())->method('update');

        $runner = new Runner($this->composerWrapper, $this->jsonFile, $this->updater);

        $runner->fix();
    }

    public function testCheckingIfAnythingBeenFixed() : void
    {
        $this->jsonFile->method('isModified')->willReturn(true);

        $runner = new Runner($this->composerWrapper, $this->jsonFile, $this->updater);

        static::assertTrue($runner->hasAnythingBeenFixed());
    }

    public function testDiff() : void
    {
        $this->jsonFile->method('diff')->willReturn('foo');

        $runner = new Runner($this->composerWrapper, $this->jsonFile, $this->updater);

        static::assertSame('foo', $runner->diff());
    }

    public function testRunningUpdates() : void
    {
        $this->updater->expects(static::once())->method('update');

        $runner = new Runner($this->composerWrapper, $this->jsonFile, $this->updater);

        $runner->runUpdates();
    }

    public function testSaving() : void
    {
        $this->jsonFile->expects(static::once())->method('save');

        $runner = new Runner($this->composerWrapper, $this->jsonFile, $this->updater);

        $runner->save();
    }
}
