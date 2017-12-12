<?php

namespace Tests\Command;

use ComposerJsonFixer\Command\ReadmeCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\ApplicationTester;

/**
 * @covers \ComposerJsonFixer\Command\ReadmeCommand
 */
final class ReadmeCommandTest extends TestCase
{
    public function testReadmeIsUpToDate()
    {
        $application = new Application();
        $command     = new ReadmeCommand('readme');

        $application->add($command);
        $application->setDefaultCommand($command->getName(), true);
        $application->setAutoExit(false);
        $application->setCatchExceptions(false);

        $tester = new ApplicationTester($application);

        $tester->run([]);

        $this->assertStringEqualsFile(
            __DIR__ . '/../../README.md',
            $tester->getDisplay()
        );
    }
}
