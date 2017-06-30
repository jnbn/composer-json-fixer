<?php

namespace ComposerJsonFixer;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Process\Process;

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
        $this->execute('composer self-update --quiet --stable');

        $this->file->save();

        $this->removeLockAndVendors();

        $data = $this->file->data();

        if (isset($data['require'])) {
            $this->execute(sprintf(
                'composer require %s --working-dir=%s %s',
                '--no-interaction --no-plugins --no-scripts --quiet --sort-packages --update-with-dependencies',
                $this->file->dir(),
                implode(' ', $this->preparePackages($data['require']))
            ));
        }

        if (isset($data['require-dev'])) {
            $this->execute(sprintf(
                'composer require %s --working-dir=%s %s',
                '--dev --no-interaction --no-plugins --no-scripts --quiet --sort-packages --update-with-dependencies',
                $this->file->dir(),
                implode(' ', $this->preparePackages($data['require-dev']))
            ));
        }

        $file = new File($this->file->dir());
        $this->file->update($file->data());
    }

    private function removeLockAndVendors()
    {
        $finder = (new Finder())->directories()->in($this->file->dir())->depth(0)->name('vendor');
        if ($finder->count() === 1) {
            $iterator = $finder->getIterator();
            $iterator->rewind();
            (new Filesystem())->remove($iterator->current()->getRealpath());
        }

        $finder = (new Finder())->files()->in($this->file->dir())->depth(0)->name('composer.lock');
        if ($finder->count() === 1) {
            $iterator = $finder->getIterator();
            $iterator->rewind();
            (new Filesystem())->remove($iterator->current()->getRealpath());
        }
    }

    /**
     * @param string $command
     *
     * @throws \Exception
     */
    private function execute($command)
    {
        $process = new Process($command);
        $process->run();

        if ($process->getExitCode() !== 0) {
            throw new \Exception(sprintf('Command failed: %s', $process->getErrorOutput()));
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
