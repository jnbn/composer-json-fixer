<?php

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

    protected function setUp()
    {
        $this->composerWrapper = $this->createMock(ComposerWrapper::class);
        $this->jsonFile        = $this->createMock(JsonFile::class);
        $this->updater         = $this->createMock(Updater::class);
    }

    public function testFixing()
    {
        $this->jsonFile->method('data')->willReturn([]);
        $this->jsonFile->expects($this->once())->method('update');

        $runner = new Runner($this->composerWrapper, $this->jsonFile, $this->updater);

        $runner->fix();
    }

    public function testCheckingIfAnythingBeenFixed()
    {
        $this->jsonFile->method('isModified')->willReturn(true);

        $runner = new Runner($this->composerWrapper, $this->jsonFile, $this->updater);

        $this->assertTrue($runner->hasAnythingBeenFixed());
    }

    public function testDiff()
    {
        $this->jsonFile->method('diff')->willReturn('foo');

        $runner = new Runner($this->composerWrapper, $this->jsonFile, $this->updater);

        $this->assertSame('foo', $runner->diff());
    }

    public function testRunningUpdates()
    {
        $this->updater->expects($this->once())->method('update');

        $runner = new Runner($this->composerWrapper, $this->jsonFile, $this->updater);

        $runner->runUpdates();
    }

    public function testSaving()
    {
        $this->jsonFile->expects($this->once())->method('save');

        $runner = new Runner($this->composerWrapper, $this->jsonFile, $this->updater);

        $runner->save();
    }
}
