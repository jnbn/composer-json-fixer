<?php

namespace ComposerJsonFixer;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\ProcessBuilder;

class Updater
{
    /** @var File */
    private $file;

    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * @throws \Exception
     */
    public function update()
    {
        $this->executeComposer('self-update', ['--stable']);

        $filesystem = new Filesystem();
        $filesystem->remove($this->file->dir() . '/composer.lock');
        $filesystem->remove($this->file->dir() . '/vendor');

        $data = $this->file->data();

        if (isset($data['require'])) {
            $this->executeComposerRequire($this->preparePackages($data['require']));
        }

        if (isset($data['require-dev'])) {
            $this->executeComposerRequire(array_merge(
                ['--dev'],
                $this->preparePackages($data['require-dev'])
            ));
        }

        $file = new File($this->file->dir());
        $this->file->update($file->data());
    }

    /**
     * @param array $arguments
     *
     * @throws \Exception
     */
    private function executeComposerRequire(array $arguments)
    {
        $this->executeComposer(
            'require',
            array_merge(
                [
                    '--no-interaction',
                    '--no-plugins',
                    '--no-scripts',
                    '--sort-packages',
                    '--update-with-dependencies',
                ],
                $arguments
            )
        );
    }

    /**
     * @param string $command
     * @param array  $arguments
     *
     * @throws \Exception
     */
    private function executeComposer($command, array $arguments)
    {
        $process = (new ProcessBuilder(
            array_merge(
                [
                    'composer',
                    $command,
                    '--quiet',
                ],
                $arguments
            )
        ))
            ->setWorkingDirectory($this->file->dir())->getProcess();

        $process->run();

        if ($process->getExitCode() !== 0) {
            throw new \Exception(sprintf('Command "composer require" failed: %s', $process->getErrorOutput()));
        }
    }

    /**
     * @param array $requires
     *
     * @return array
     */
    private function preparePackages(array $requires)
    {
        $packages = [];
        foreach ($requires as $name => $version) {
            if (mb_strpos($name, 'ext-') === 0
                || $name === 'roave/security-advisories' && $version === 'dev-master') {
                continue;
            }
            $packages[] = $name;
        }

        return $packages;
    }
}
