<?php

declare(strict_types = 1);

namespace Tests;

use ComposerJsonFixer\JsonFile;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ComposerJsonFixer\JsonFile
 */
final class JsonFileTest extends TestCase
{
    public function testWhenNoFile() : void
    {
        $directory = vfsStream::setup();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File "composer.json" not found');

        new JsonFile($directory->url());
    }

    public function testWhenContentIsNotJson() : void
    {
        $directory = vfsStream::setup();
        vfsStream::newFile('composer.json')
            ->at($directory)
            ->setContent('foo');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File "composer.json" does not contain valid JSON');

        new JsonFile($directory->url());
    }

    public function testDirectoryIsCorrect() : void
    {
        $directory = vfsStream::setup();
        vfsStream::newFile('composer.json')
            ->at($directory)
            ->setContent('{"foo":"bar"}');

        $jsonFile = new JsonFile($directory->url());

        static::assertSame($directory->url(), $jsonFile->directory());
        static::assertSame(['foo' => 'bar'], $jsonFile->data());
        static::assertFalse($jsonFile->isModified());
    }

    public function testUpdating() : void
    {
        $directory = vfsStream::setup();
        vfsStream::newFile('composer.json')
            ->at($directory)
            ->setContent('{}');

        $jsonFile = new JsonFile($directory->url());
        $jsonFile->update(['name' => 'Kuba Werłos', 'package' => 'foo/bar', 'version' => 1.0]);
        $jsonFile->save();

        $expectedContent = '{
    "name": "Kuba Werłos",
    "package": "foo/bar",
    "version": 1.0
}
';

        static::assertSame($expectedContent, $directory->getChild('composer.json')->getContent());
        static::assertTrue($jsonFile->isModified());
    }

    public function testDiff() : void
    {
        $directory = vfsStream::setup();
        vfsStream::newFile('composer.json')
            ->at($directory)
            ->setContent('{}');

        $jsonFile = new JsonFile($directory->url());
        $jsonFile->update(['foo' => 'bar']);

        static::assertContains('+    "foo": "bar"', $jsonFile->diff());
    }
}
