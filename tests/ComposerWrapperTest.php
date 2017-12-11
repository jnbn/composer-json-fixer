<?php

namespace Tests;

use ComposerJsonFixer\ComposerExecutable;
use ComposerJsonFixer\ComposerWrapper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

/**
 * @covers \ComposerJsonFixer\ComposerWrapper
 */
final class ComposerWrapperTest extends TestCase
{
    const TMP_DIRECTORY = __DIR__ . '/tmp';

    protected function setUp()
    {
        $filesystem = new Filesystem();
        $filesystem->remove(self::TMP_DIRECTORY);
        $filesystem->mkdir(self::TMP_DIRECTORY);
    }

    protected function tearDown()
    {
        $filesystem = new Filesystem();
        $filesystem->remove(self::TMP_DIRECTORY);
    }

    public function testWhenComposerExecutableNotFound()
    {
        $composerExecutable = $this->createMock(ComposerExecutable::class);
        $composerExecutable->method('tryToGetFromUserPath')->willReturn('');
        $composerExecutable->method('tryToGetLocalComposerPhar')->willReturn(null);
        $composerExecutable->method('tryToDownloadComposerPhar')->willReturn(null);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Please install Composer');

        new ComposerWrapper($composerExecutable, self::TMP_DIRECTORY);
    }

    public function testValidatingIncorrectFile()
    {
        \file_put_contents(self::TMP_DIRECTORY . '/composer.json', '{}');

        $composerWrapper = new ComposerWrapper(new ComposerExecutable(), self::TMP_DIRECTORY);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File "composer.json" did not pass validation');

        $this->assertNull($composerWrapper->callValidate());
    }

    public function testValidatingCorrectFile()
    {
        $content = \json_encode(['name' => 'foo/bar', 'description' => 'Hello world']);

        \file_put_contents(self::TMP_DIRECTORY . '/composer.json', $content);

        $composerWrapper = new ComposerWrapper(new ComposerExecutable(), self::TMP_DIRECTORY);

        $this->assertNull($composerWrapper->callValidate());
    }

    public function testSelfUpdate()
    {
        $composerWrapper = new ComposerWrapper(new ComposerExecutable(), self::TMP_DIRECTORY);

        $this->assertNull($composerWrapper->callSelfUpdate());
    }

    public function testRequire()
    {
        $content = \json_encode(['name' => 'foo/bar', 'description' => 'Hello world']);

        \file_put_contents(self::TMP_DIRECTORY . '/composer.json', $content);

        $composerWrapper = new ComposerWrapper(new ComposerExecutable(), self::TMP_DIRECTORY);

        $this->assertNull($composerWrapper->callRequire(['psr/log'], true));
    }

    public function testRequireWithFakeRepository()
    {
        $content = \json_encode(['name' => 'foo/bar', 'description' => 'Hello world']);

        \file_put_contents(self::TMP_DIRECTORY . '/composer.json', $content);

        $composerWrapper = new ComposerWrapper(new ComposerExecutable(), self::TMP_DIRECTORY);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Command "composer require" failed');

        $composerWrapper->callRequire(['foo/bar'], true);
    }
}
