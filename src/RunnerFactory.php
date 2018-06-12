<?php

declare(strict_types = 1);

namespace ComposerJsonFixer;

class RunnerFactory
{
    /**
     * @param string $directory
     *
     * @return Runner
     */
    public static function create($directory)
    {
        $composerWrapper = new ComposerWrapper(new ComposerExecutable(), $directory);

        $jsonFile = new JsonFile($directory);

        $updater = new Updater($composerWrapper, $jsonFile);

        return new Runner($composerWrapper, $jsonFile, $updater);
    }
}
