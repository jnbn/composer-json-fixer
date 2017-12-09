<?php

namespace ComposerJsonFixer;

use Symfony\Component\Process\Process;

class ComposerWrapper
{
    /** @var string */
    private $composer;

    public function __construct()
    {
        $this->composer = \trim(\shell_exec('which composer || which composer.phar'));

        if (empty($this->composer)) {
            $tmpComposer = \sys_get_temp_dir() . '/composer.phar';
            if (\copy('https://getcomposer.org/composer.phar', $tmpComposer)) {
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

    public function validate($directory)
    {
        if (!$this->isFound()) {
            return;
        }

        $process = new Process(
            \sprintf(
                '%s validate --no-check-all --no-check-lock --no-check-publish',
                $this->composer
            ),
            $directory
        );

        $process->run();

        if ($process->getExitCode() !== 0) {
            throw new \Exception(\sprintf(
                'File "composer.json" did not pass validation: %s',
                $process->getErrorOutput()
            ));
        }
    }
}
