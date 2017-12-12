<?php

namespace Tests\Command;

use ComposerJsonFixer\Command\FixerCommand;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

/**
 * @covers \ComposerJsonFixer\Command\FixerCommand
 */
final class FixerCommandTest extends TestCase
{
    /** @var vfsStreamDirectory */
    private $directory;

    /** @var ApplicationTester */
    private $tester;

    protected function setUp()
    {
        $this->directory = vfsStream::setup();

        $application = new Application();
        $command     = new FixerCommand('composer-json-fixer');

        $application->add($command);
        $application->setDefaultCommand($command->getName(), true);
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $this->tester = new ApplicationTester($application);
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
        $this->assertContains(\sprintf('The "%s" directory does not exist', $path), $this->tester->getDisplay());
    }

    public function testDirectoryWithoutComposerJson()
    {
        $this->tester->run([
            'directory' => $this->directory->url(),
        ]);

        $this->assertSame(2, $this->tester->getStatusCode());
        $this->assertContains('File "composer.json" not found', $this->tester->getDisplay());
    }

    public function testInvalidFile()
    {
        vfsStream::newFile('composer.json')
            ->at($this->directory)
            ->setContent('}');

        $this->tester->run([
            'directory' => $this->directory->url(),
        ]);

        $this->assertSame(2, $this->tester->getStatusCode());
        $this->assertContains('File "composer.json" does not contain valid JSON', $this->tester->getDisplay());
    }

    public function testDryRunNotChangingFile()
    {
        $composerJson = vfsStream::newFile('composer.json')
            ->at($this->directory)
            ->setContent('{"NaMe":"Foo"}');

        $this->tester->run([
            '--dry-run' => true,
            'directory' => $this->directory->url(),
        ]);

        $this->assertSame(1, $this->tester->getStatusCode());
        $this->assertSame('{"NaMe":"Foo"}', $composerJson->getContent());
        $this->assertContains('"name": "foo', $this->tester->getDisplay());
    }

    public function testFixing()
    {
        $composerJson = vfsStream::newFile('composer.json')
            ->at($this->directory)
            ->setContent('{"NaMe":"Foo"}');

        $this->tester->run([
            'directory' => $this->directory->url(),
        ]);

        $this->assertSame(1, $this->tester->getStatusCode());
        $this->assertContains('"name": "foo', $composerJson->getContent());
    }

    public function testSelfComposer()
    {
        $this->tester->run([
            'directory' => __DIR__ . '/../..',
        ]);

        $this->assertSame(0, $this->tester->getStatusCode());
    }

    public function testFixingWithUpdateAndFakeRepository()
    {
        vfsStream::newFile('composer.json')
            ->at($this->directory)
            ->setContent('{"require":{"foo/bar": "^1.0"}}');

        $this->tester->run([
            '--with-updates' => true,
            'directory'      => $this->directory->url(),
        ]);

        $this->assertSame(2, $this->tester->getStatusCode());
        $this->assertContains('Command "composer require" failed', $this->tester->getDisplay());
    }

    public function testWithUpdateWhenNothingToFixAndUpdate()
    {
        vfsStream::newFile('composer.json')
            ->at($this->directory)
            ->setContent("{\n    \"license\": \"proprietary\"\n}\n");

        $this->tester->run([
            '--with-updates' => true,
            'directory'      => $this->directory->url(),
        ]);

        $this->assertSame(0, $this->tester->getStatusCode());
        $this->assertContains('There is nothing to fix', $this->tester->getDisplay());
    }
}
