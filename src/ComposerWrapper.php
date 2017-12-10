<?php

namespace ComposerJsonFixer;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

class ComposerWrapper
{
    /** @var string */
    private $composer;

    /** @var string */
    private $directory;

    public function __construct($directory)
    {
        $this->composer  = \trim(\shell_exec('which composer'));
        $this->directory = $directory;

        if (empty($this->composer)) {
            $finder = Finder::create()->files()->in($directory)->depth(0)->name('composer.phar');
            if ($finder->count() === 1) {
                $iterator = $finder->getIterator();
                $iterator->rewind();
                $this->composer = PHP_BINDIR . '/php ' . $iterator->current()->getPathname();
            }
        }

        if (empty($this->composer)) {
            $tmpComposer = \sys_get_temp_dir() . '/composer.phar';
            if (\file_exists($tmpComposer)) {
                $this->composer = PHP_BINDIR . '/php ' . $tmpComposer;
            } elseif (\copy('https://getcomposer.org/composer.phar', $tmpComposer)) {
                $this->composer = PHP_BINDIR . '/php ' . $tmpComposer;
            }
        }
    }

    /**
     * @return bool
     */
    public function isFound()
    {
        return !empty($this->composer);
    }

    public function callValidate()
    {
        if (!$this->isFound()) {
            return;
        }

        $process = new Process(
            \sprintf(
                '%s validate --no-check-all --no-check-lock --no-check-publish --quiet',
                $this->composer
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
                $this->composer
            )
        );

        $process->run();
    }

    public function callRequire(array $packages, $isDev = false)
    {
        $flags = '--no-interaction --no-plugins --no-scripts --sort-packages --update-with-dependencies';
        if ($isDev) {
            $flags .= ' --dev';
        }

        $process = new Process(
            \sprintf(
                '%s require %s %s',
                $this->composer,
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
