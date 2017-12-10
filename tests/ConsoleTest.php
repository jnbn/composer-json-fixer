<?php

namespace Tests;

use ComposerJsonFixer\Console\Application;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Tester\ApplicationTester;

/**
 * @covers \ComposerJsonFixer\Console\Application
 * @covers \ComposerJsonFixer\Console\Command
 */
final class ConsoleTest extends TestCase
{
    const COMPOSER_JSON_BAD_FORMAT = '{"NaMe":"Foo"}';

    /** @var vfsStreamDirectory */
    private $directory;

    /** @var ApplicationTester */
    private $tester;

    protected function setUp()
    {
        $this->directory = vfsStream::setup();

        $application = new Application();
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);
        $this->tester = new ApplicationTester($application);
    }

    public function testNonExistentOption()
    {
        $this->tester->run([
            '--non-existent-option' => true,
            'directory'             => $this->directory->url(),
        ]);

        $this->assertSame(2, $this->tester->getStatusCode());
        $this->assertContains('option does not exist', $this->tester->getDisplay());
    }

    public function testImpossibleToDryRunAndWithUpdate()
    {
        $this->tester->run([
            '--dry-run'      => true,
            '--with-updates' => true,
            'directory'      => $this->directory->url(),
        ]);

        $this->assertSame(2, $this->tester->getStatusCode());
        $this->assertContains(
            'It is impossible to run with both "--dry-run" and "--with-updates"',
            $this->tester->getDisplay()
        );
    }

    public function testIncorrectPath()
    {
        $path = $this->directory->url() . '/incorrect/path';

        $this->tester->run([
            'directory' => $path,
        ]);

        $this->assertSame(2, $this->tester->getStatusCode());
        $this->assertContains("The \"$path\" directory does not exist", $this->tester->getDisplay());
    }

    public function testDirectoryWithoutComposerJson()
    {
        $this->tester->run([
            'directory' => $this->directory->url(),
        ]);

        $this->assertSame(2, $this->tester->getStatusCode());
        $this->assertContains('File "composer.json" not found', $this->tester->getDisplay());
    }

    public function testDryRunNotChangingFile()
    {
        $composerJson = vfsStream::newFile('composer.json')
            ->at($this->directory)
            ->setContent(self::COMPOSER_JSON_BAD_FORMAT);

        $this->tester->run([
            '--dry-run' => true,
            'directory' => $this->directory->url(),
        ]);

        $this->assertSame(1, $this->tester->getStatusCode());
        $this->assertSame(self::COMPOSER_JSON_BAD_FORMAT, $composerJson->getContent());
        $this->assertContains('"name": "foo', $this->tester->getDisplay());
    }
}
