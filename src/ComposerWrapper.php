<?php

declare(strict_types=1);

namespace ComposerJsonFixer;

use Symfony\Component\Process\Process;

class ComposerWrapper
{
    /** @var ?string */
    private $composerExecutable;

    /** @var string */
    private $directory;

    /**
     * @throws \Exception
     */
    public function __construct(ComposerExecutable $composerExecutable, string $directory)
    {
        $this->directory = $directory;

        $this->composerExecutable = $composerExecutable->tryToGetFromUserPath();
        if ($this->composerExecutable === null) {
            $this->composerExecutable = $composerExecutable->tryToGetLocalComposerPhar($directory);
        }
        if ($this->composerExecutable === null) {
            $this->composerExecutable = $composerExecutable->tryToDownloadComposerPhar();
        }
        if ($this->composerExecutable === '') {
            throw new \Exception('Please install Composer (or put composer.phar next to composer.json');
        }
    }

    /**
     * @throws \Exception
     */
    public function callValidate() : void
    {
        $process = new Process([
            $this->composerExecutable,
            'validate',
            '--no-check-all',
            '--no-check-lock',
            '--quiet',
        ], $this->directory);

        $process->run();

        if ($process->getExitCode() !== 0) {
            throw new \Exception(
                \sprintf('File "composer.json" did not pass validation: %s', $process->getErrorOutput())
            );
        }
    }

    public function callSelfUpdate() : void
    {
        $process = new Process([
            $this->composerExecutable,
            'self-update',
            '--stable',
            '--quiet',
        ]);

        $process->run();
    }

    /**
     * @throws \Exception
     */
    public function callRequire(array $packages, bool $isDev) : void
    {
        $flags = '--no-interaction --no-plugins --no-scripts --sort-packages --update-with-dependencies';
        if ($isDev) {
            $flags .= ' --dev';
        }

        $process = Process::fromShellCommandline(
            \sprintf('%s require %s %s', $this->composerExecutable, $flags, \implode(' ', $packages)), $this->directory
        );
        $process->setTimeout(3600);

        $process->run();

        if ($process->getExitCode() !== 0) {
            throw new \Exception(
                \sprintf('Command "composer require" failed: %s', $process->getErrorOutput())
            );
        }
    }
}
