<?php

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

    public function testTryingToGetFromUserPath()
    {
        $composerExecutable = new ComposerExecutable();

        $this->assertSame(\trim(\shell_exec('which composer')), $composerExecutable->tryToGetFromUserPath());
    }

    public function testTryingToGetLocalComposerPharWhenThereIsNot()
    {
        $composerExecutable = new ComposerExecutable();

        $this->assertNull($composerExecutable->tryToGetLocalComposerPhar(self::TMP_DIRECTORY));
    }

    public function testTryingToGetLocalComposerPharWhenThereIs()
    {
        \file_put_contents(self::TMP_DIRECTORY . '/composer.phar', '<?php echo "foo";');

        $composerExecutable = new ComposerExecutable();
        $executable         = $composerExecutable->tryToGetLocalComposerPhar(self::TMP_DIRECTORY);

        $process = new Process($executable);
        $process->run();

        $this->assertSame('foo', $process->getOutput());
    }

    public function testTryingToDownloadComposerPhar()
    {
        \file_put_contents(\sys_get_temp_dir() . '/composer.phar', '<?php echo "foo";');

        $composerExecutable = new ComposerExecutable();
        $executable         = $composerExecutable->tryToDownloadComposerPhar();

        $process = new Process($executable . ' --version');
        $process->run();

        $this->assertContains('Composer version', $process->getOutput());
    }
}
