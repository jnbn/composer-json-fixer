<?php

namespace Tests;

use ComposerJsonFixer\JsonFile;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ComposerJsonFixer\JsonFile
 */
final class JsonFileTest extends TestCase
{
    public function testWhenNoFile()
    {
        $directory = vfsStream::setup();

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File "composer.json" not found');

        new JsonFile($directory->url());
    }

    public function testWhenContentIsNotJson()
    {
        $directory = vfsStream::setup();
        vfsStream::newFile('composer.json')
            ->at($directory)
            ->setContent('foo');

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('File "composer.json" does not contain valid JSON');

        new JsonFile($directory->url());
    }

    public function testDirectoryIsCorrect()
    {
        $directory = vfsStream::setup();
        vfsStream::newFile('composer.json')
            ->at($directory)
            ->setContent('{"foo":"bar"}');

        $jsonFile = new JsonFile($directory->url());

        $this->assertSame($directory->url(), $jsonFile->directory());
        $this->assertSame(['foo' => 'bar'], $jsonFile->data());
        $this->assertFalse($jsonFile->isModified());
    }

    public function testUpdating()
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

        $this->assertSame($expectedContent, $directory->getChild('composer.json')->getContent());
        $this->assertTrue($jsonFile->isModified());
    }

    public function testDiff()
    {
        $directory = vfsStream::setup();
        vfsStream::newFile('composer.json')
            ->at($directory)
            ->setContent('{}');

        $jsonFile = new JsonFile($directory->url());
        $jsonFile->update(['foo' => 'bar']);

        $this->assertContains('+    "foo": "bar"', $jsonFile->diff());
    }
}
