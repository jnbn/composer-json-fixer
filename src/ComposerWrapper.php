<?php

namespace ComposerJsonFixer;

use Symfony\Component\Process\Process;

class ComposerWrapper
{
    /** @var ?string */
    private $composerExecutable;

    /** @var string */
    private $directory;

    /**
     * @param ComposerExecutable $composerExecutable
     * @param string             $directory
     *
     * @throws \Exception
     */
    public function __construct(ComposerExecutable $composerExecutable, $directory)
    {
        $this->directory = $directory;

        $this->composerExecutable = $composerExecutable->tryToGetFromUserPath();
        if (empty($this->composerExecutable)) {
            $this->composerExecutable = $composerExecutable->tryToGetLocalComposerPhar($directory);
        }
        if (empty($this->composerExecutable)) {
            $this->composerExecutable = $composerExecutable->tryToDownloadComposerPhar();
        }
        if (empty($this->composerExecutable)) {
            throw new \Exception('Please install Composer (or put composer.phar next to composer.json');
        }
    }

    /**
     * @throws \Exception
     */
    public function callValidate()
    {
        $process = new Process(
            \sprintf(
                '%s validate --no-check-all --no-check-lock --quiet',
                $this->composerExecutable
            ),
            $this->directory
        );

        $process->run();

        if ($process->getExitCode() !== 0) {
            throw new \Exception(\sprintf(
                'File "composer.json" did not pass validation: %s',
                $process->getErrorOutput()
            ));
        }
    }

    public function callSelfUpdate()
    {
        $process = new Process(
            \sprintf(
                '%s self-update --stable --quiet',
                $this->composerExecutable
            )
        );

        $process->run();
    }

    /**
     * @param array $packages
     * @param bool  $isDev
     *
     * @throws \Exception
     */
    public function callRequire(array $packages, $isDev = false)
    {
        $flags = '--no-interaction --no-plugins --no-scripts --sort-packages --update-with-dependencies';
        if ($isDev) {
            $flags .= ' --dev';
        }

        $process = new Process(
            \sprintf(
                '%s require %s %s',
                $this->composerExecutable,
                $flags,
                \implode(' ', $packages)
            ),
            $this->directory
        );

        $process->run();

        if ($process->getExitCode() !== 0) {
            throw new \Exception(\sprintf('Command "composer require" failed: %s', $process->getErrorOutput()));
        }
    }
}
