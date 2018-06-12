<?php

declare(strict_types = 1);

namespace Tests;

use ComposerJsonFixer\Runner;
use ComposerJsonFixer\RunnerFactory;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ComposerJsonFixer\RunnerFactory
 */
final class RunnerFactoryTest extends TestCase
{
    public function testCreation() : void
    {
        $directory = vfsStream::setup();
        vfsStream::newFile('composer.json')
            ->at($directory)
            ->setContent('{}');

        static::assertInstanceOf(Runner::class, RunnerFactory::create($directory->url()));
    }
}
