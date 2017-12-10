<?php

namespace ComposerJsonFixer;

use Symfony\Component\Filesystem\Filesystem;

class Updater
{
    /** @var ComposerWrapper */
    private $composerWrapper;

    /** @var JsonFile */
    private $jsonFile;

    public function __construct(ComposerWrapper $composerWrapper, JsonFile $jsonFile)
    {
        $this->composerWrapper = $composerWrapper;
        $this->jsonFile        = $jsonFile;
    }

    /**
     * @throws \Exception
     */
    public function update()
    {
        $this->composerWrapper->selfUpdate();

        $filesystem = new Filesystem();
        $filesystem->remove($this->jsonFile->directory() . '/composer.lock');
        $filesystem->remove($this->jsonFile->directory() . '/vendor');

        $data = $this->jsonFile->data();

        if (isset($data['require'])) {
            $this->composerWrapper->require($this->preparePackages($data['require']));
        }

        if (isset($data['require-dev'])) {
            $this->composerWrapper->require($this->preparePackages($data['require-dev']), true);
        }

        $file = new JsonFile($this->jsonFile->directory());
        $this->jsonFile->update($file->data());
    }

    /**
     * @param array $requires
     *
     * @return array
     */
    private function preparePackages(array $requires)
    {
        $requires = \array_filter($requires, function ($name) {
            return \mb_strpos($name, 'ext-') !== 0;
        }, ARRAY_FILTER_USE_KEY);

        $requires = \array_filter($requires, function ($version) {
            return \mb_strpos($version, 'dev-') !== 0;
        });

        return \array_keys($requires);
    }
}
