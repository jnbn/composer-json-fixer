<?php

declare(strict_types=1);

namespace ComposerJsonFixer;

class RunnerFactory
{
    public static function create(string $directory) : Runner
    {
        $composerWrapper = new ComposerWrapper(new ComposerExecutable(), $directory);

        $jsonFile = new JsonFile($directory);

        $updater = new Updater($composerWrapper, $jsonFile);

        return new Runner($composerWrapper, $jsonFile, $updater);
    }
}
