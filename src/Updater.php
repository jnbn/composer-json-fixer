<?php

namespace ComposerJsonFixer;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;

class Updater
{
    /** @var JsonFile */
    private $jsonFile;

    public function __construct(JsonFile $jsonFile)
    {
        $this->jsonFile = $jsonFile;
    }

    /**
     * @throws \Exception
     */
    public function update()
    {
        $this->executeComposer('self-update', ['--stable']);

        $filesystem = new Filesystem();
        $filesystem->remove($this->jsonFile->directory() . '/composer.lock');
        $filesystem->remove($this->jsonFile->directory() . '/vendor');

        $data = $this->jsonFile->data();

        if (isset($data['require'])) {
            $this->executeComposerRequire($this->preparePackages($data['require']));
        }

        if (isset($data['require-dev'])) {
            $this->executeComposerRequire(\array_merge(
                ['--dev'],
                $this->preparePackages($data['require-dev'])
            ));
        }

        $file = new JsonFile($this->jsonFile->directory());
        $this->jsonFile->update($file->data());
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
            \array_merge(
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
        $process = new Process(
            \array_merge(
                [
                    'composer',
                    $command,
                    '--quiet',
                ],
                $arguments
            ),
            $this->jsonFile->directory()
        );

        $process->run();

        if ($process->getExitCode() !== 0) {
            throw new \Exception(\sprintf('Command "composer require" failed: %s', $process->getErrorOutput()));
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
            if (\mb_strpos($name, 'ext-') === 0
                || $name === 'roave/security-advisories' && $version === 'dev-master') {
                continue;
            }
            $packages[] = $name;
        }

        return $packages;
    }
}
