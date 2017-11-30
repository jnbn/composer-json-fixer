<?php

namespace Tests;

use ComposerJsonFixer\Console\Application;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\ApplicationTester;
use Symfony\Component\Filesystem\Filesystem;

class FunctionalTest extends TestCase
{
    /** @var ApplicationTester */
    private $tester;

    public static function setUpBeforeClass()
    {
        eval(sprintf('
            namespace Symfony\Component\Process {
                function proc_open(...$args)
                {
                    return \Tests\ProcOpenMock::call(...$args);
                }
            }
        '));
    }

    protected function setUp()
    {
        $application = new Application();
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $this->tester = new ApplicationTester($application);
    }

    protected function tearDown()
    {
        if (file_exists(__DIR__ . '/composer.json')) {
            (new Filesystem())->remove(__DIR__ . '/composer.json');
        }
        if (file_exists(__DIR__ . '/composer.lock')) {
            (new Filesystem())->remove(__DIR__ . '/composer.lock');
        }
        if (is_dir(__DIR__ . '/vendor')) {
            (new Filesystem())->remove(__DIR__ . '/vendor');
        }
    }

    public function testImpossibleToDryRunAndWithUpdate()
    {
        $this->tester->run([
            '--dry-run'      => true,
            '--with-updates' => true,
            'path'           => '',
        ]);

        $this->assertSame(2, $this->tester->getStatusCode());
        $this->assertContains(
            'It is impossible to run with both "--dry-run" and "--with-updates"',
            $this->tester->getDisplay()
        );
    }

    public function testNonExistentOption()
    {
        $this->tester->run([
            '--non-existent-option'=> true,
            'path'                 => '',
        ]);

        $this->assertSame(2, $this->tester->getStatusCode());
        $this->assertContains('option does not exist', $this->tester->getDisplay());
    }

    public function testIncorrectPath()
    {
        $path = __DIR__ . '/incorrect/path';

        $this->tester->run([
            'path' => $path,
        ]);

        $this->assertSame(2, $this->tester->getStatusCode());
        $this->assertContains("The \"$path\" directory does not exist", $this->tester->getDisplay());
    }

    public function testDirectoryWithoutComposerJson()
    {
        $this->tester->run([
            'path' => __DIR__,
        ]);

        $this->assertSame(2, $this->tester->getStatusCode());
        $this->assertContains('File "composer.json" not found', $this->tester->getDisplay());
    }

    public function testDryRunNotChangingFile()
    {
        $original = __DIR__ . '/stubs/a-lot-to-fix.json';
        $tested   = __DIR__ . '/composer.json';

        copy($original, $tested);

        $this->tester->run([
            '--dry-run' => true,
            'path'      => dirname($tested),
        ]);

        $this->assertSame(1, $this->tester->getStatusCode());
        $this->assertFileEquals($original, $tested);
    }

    public function testInvalidFile()
    {
        $this->doTest(__DIR__ . '/stubs/invalid-json.json', [], 2);
        $this->assertContains('File "composer.json" did not pass validation', $this->tester->getDisplay());
    }

    public function testSelfComposer()
    {
        $this->doTest(__DIR__ . '/../composer.json', [], 0);
    }

    public function testFixing()
    {
        $this->doTest(__DIR__ . '/stubs/a-lot-to-fix.json', [], 1);
        $this->assertFileEquals(__DIR__ . '/stubs/a-lot-to-fix-fixed.json', __DIR__ . '/composer.json');
    }

    public function testWithUpdateAndFakeRepository()
    {
        $this->doTest(__DIR__ . '/stubs/fake-require.json', ['--with-updates' => true], 2);
        $this->assertContains('Command "composer require" failed', $this->tester->getDisplay());
    }

    public function testWithUpdateWhenNothingToFixAndUpdate()
    {
        $this->doTest(__DIR__ . '/stubs/psr-fixed-updated.json', ['--with-updates' => true], 0);
    }

    public function testWithUpdateWhenNothingToFix()
    {
        $this->doTest(__DIR__ . '/stubs/psr-fixed-to-update.json', ['--with-updates' => true], 1);
    }

    public function testWithUpdateWhenNothingToUpdate()
    {
        $this->doTest(__DIR__ . '/stubs/psr-to-fix-updated.json', ['--with-updates' => true], 1);
    }

    /**
     * @param string $path
     * @param array  $options
     * @param int    $statusCode
     */
    private function doTest($path, $options, $statusCode)
    {
        copy($path, __DIR__ . '/composer.json');

        $this->tester->run(array_merge(
            $options,
            ['path' => __DIR__]
        ));

        $this->assertSame($statusCode, $this->tester->getStatusCode());
    }
}
