<?php

declare(strict_types = 1);

namespace ComposerJsonFixer;

final class Configuration
{
    /** @var string */
    private $directory;

    /** @var bool */
    private $dryRun;

    /** @var bool */
    private $upgrade;

    public function __construct(
        string $directory,
        bool $dryRun,
        bool $upgrade
    ) {
        $this->directory = $directory;
        $this->dryRun    = $dryRun;
        $this->upgrade   = $upgrade;

        if ($this->dryRun && $this->upgrade) {
            throw new \InvalidArgumentException('It is impossible to run with both dry run and upgrade');
        }
    }

    public function directory() : string
    {
        return $this->directory;
    }

    public function dryRun() : bool
    {
        return $this->dryRun;
    }

    public function upgrade() : bool
    {
        return $this->upgrade;
    }
}
