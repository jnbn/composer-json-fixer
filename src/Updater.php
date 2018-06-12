<?php

declare(strict_types = 1);

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
    public function update() : void
    {
        $this->jsonFile->save();

        $this->composerWrapper->callSelfUpdate();

        $filesystem = new Filesystem();
        $filesystem->remove($this->jsonFile->directory() . '/composer.lock');
        $filesystem->remove($this->jsonFile->directory() . '/vendor');

        $data = $this->jsonFile->data();

        if (isset($data['require'])) {
            $this->composerWrapper->callRequire($this->preparePackages($data['require']), false);
        }

        if (isset($data['require-dev'])) {
            $this->composerWrapper->callRequire($this->preparePackages($data['require-dev']), true);
        }

        $file = new JsonFile($this->jsonFile->directory());
        $this->jsonFile->update($file->data());
    }

    private function preparePackages(array $requires) : array
    {
        $requires = \array_filter($requires, static function ($name) {
            return \mb_strpos($name, 'ext-') !== 0;
        }, ARRAY_FILTER_USE_KEY);

        $requires = \array_filter($requires, static function ($version) {
            return \mb_strpos($version, 'dev-') !== 0;
        });

        return \array_keys($requires);
    }
}
