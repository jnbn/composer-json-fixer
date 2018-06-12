<?php

declare(strict_types = 1);

namespace Tests;

use ComposerJsonFixer\ComposerWrapper;
use ComposerJsonFixer\JsonFile;
use ComposerJsonFixer\Updater;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ComposerJsonFixer\Updater
 */
final class UpdaterTest extends TestCase
{
    public function testUpdating() : void
    {
        $directory = vfsStream::setup();
        vfsStream::newFile('composer.json')
            ->at($directory)
            ->setContent('{}');

        $composerWrapper = $this->createMock(ComposerWrapper::class);
        $composerWrapper->expects(static::once())->method('callSelfUpdate');
        $composerWrapper->expects(static::exactly(2))->method('callRequire')
            ->withConsecutive(
                [['foo'], false],
                [['bar'], true]
            );

        $jsonFile = $this->createMock(JsonFile::class);
        $jsonFile->method('directory')->willReturn($directory->url());
        $jsonFile->method('data')->willReturn(['require' => ['foo' => '1'], 'require-dev' => ['bar' => '1']]);

        $updater = new Updater($composerWrapper, $jsonFile);
        $updater->update();
    }
}
