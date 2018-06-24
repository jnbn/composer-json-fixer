<?php

declare(strict_types = 1);

namespace ComposerJsonFixer;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class ComposerExecutable
{
    public function tryToGetFromUserPath() : ?string
    {
        $output = \shell_exec('which composer');

        return $output === null ? null : \trim($output);
    }

    public function tryToGetLocalComposerPhar(string $directory) : ?string
    {
        $finder = Finder::create()->files()->in($directory)->depth(0)->name('composer.phar');
        if ($finder->count() === 1) {
            $iterator = $finder->getIterator();
            $iterator->rewind();

            return PHP_BINDIR . '/php ' . $iterator->current()->getPathname();
        }

        return null;
    }

    public function tryToDownloadComposerPhar() : string
    {
        $tmpComposer = \sys_get_temp_dir() . '/composer.phar';

        if (\file_exists($tmpComposer)) {
            $filesystem = new Filesystem();
            $filesystem->remove($tmpComposer);
        }

        \copy('https://getcomposer.org/composer.phar', $tmpComposer);

        return PHP_BINDIR . '/php ' . $tmpComposer;
    }
}
