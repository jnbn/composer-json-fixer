<?php

declare(strict_types = 1);

namespace Tests;

use ComposerJsonFixer\ComposerExecutable;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

/**
 * @covers \ComposerJsonFixer\ComposerExecutable
 */
final class ComposerExecutableTest extends TestCase
{
    private const TMP_DIRECTORY = __DIR__ . '/tmp';

    protected function setUp() : void
    {
        $filesystem = new Filesystem();
        $filesystem->remove(self::TMP_DIRECTORY);
        $filesystem->mkdir(self::TMP_DIRECTORY);
    }

    protected function tearDown() : void
    {
        $filesystem = new Filesystem();
        $filesystem->remove(self::TMP_DIRECTORY);
    }

    public function testTryingToGetFromUserPath() : void
    {
        $composerExecutable = new ComposerExecutable();

        static::assertSame(\trim(\shell_exec('which composer')), $composerExecutable->tryToGetFromUserPath());
    }

    public function testTryingToGetLocalComposerPharWhenThereIsNot() : void
    {
        $composerExecutable = new ComposerExecutable();

        static::assertNull($composerExecutable->tryToGetLocalComposerPhar(self::TMP_DIRECTORY));
    }

    public function testTryingToGetLocalComposerPharWhenThereIs() : void
    {
        \file_put_contents(self::TMP_DIRECTORY . '/composer.phar', '<?php echo "foo";');

        $composerExecutable = new ComposerExecutable();
        $executable         = $composerExecutable->tryToGetLocalComposerPhar(self::TMP_DIRECTORY);

        $process = new Process($executable);
        $process->run();

        static::assertSame('foo', $process->getOutput());
    }

    public function testTryingToDownloadComposerPhar() : void
    {
        \file_put_contents(\sys_get_temp_dir() . '/composer.phar', '<?php echo "foo";');

        $composerExecutable = new ComposerExecutable();
        $executable         = $composerExecutable->tryToDownloadComposerPhar();

        $process = new Process($executable . ' --version');
        $process->run();

        static::assertContains('Composer version', $process->getOutput());
    }
}
