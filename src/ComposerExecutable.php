<?php

declare(strict_types = 1);

namespace ComposerJsonFixer;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ComposerExecutable
{
    /**
     * @return string
     */
    public function tryToGetFromUserPath()
    {
        return \trim(\shell_exec('which composer'));
    }

    /**
     * @param string $directory
     *
     * @return ?string
     */
    public function tryToGetLocalComposerPhar($directory)
    {
        $finder = Finder::create()->files()->in($directory)->depth(0)->name('composer.phar');
        if ($finder->count() === 1) {
            $iterator = $finder->getIterator();
            $iterator->rewind();

            return PHP_BINDIR . '/php ' . $iterator->current()->getPathname();
        }
    }

    /**
     * @return ?string
     */
    public function tryToDownloadComposerPhar()
    {
        $tmpComposer = \sys_get_temp_dir() . '/composer.phar';

        if (\file_exists($tmpComposer)) {
            $filesystem = new Filesystem();
            $filesystem->remove($tmpComposer);
        }

        if (\copy('https://getcomposer.org/composer.phar', $tmpComposer)) {
            return PHP_BINDIR . '/php ' . $tmpComposer;
        }
    }
}
