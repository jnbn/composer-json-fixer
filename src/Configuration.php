<?php

declare(strict_types=1);

namespace ComposerJsonFixer;

final class Configuration
{
    /** @var string */
    private $directory;

    /** @var bool */
    private $dryRun;

    /** @var bool */
    private $upgrade;

    /** @var bool */
    private $upgradeDev;

    public function __construct(
        string $directory,
        bool $dryRun,
        bool $upgrade,
        bool $upgradeDev
    ) {
        $this->directory = $directory;
        $this->dryRun = $dryRun;
        $this->upgrade = $upgrade;
        $this->upgradeDev = $upgradeDev;

        if ($this->dryRun) {
            if ($this->upgrade) {
                throw new \InvalidArgumentException('It is impossible to run with both dry run and upgrade');
            }
            if ($this->upgradeDev) {
                throw new \InvalidArgumentException('It is impossible to run with both dry run and upgrade dev');
            }
        }

        if ($this->upgrade && $this->upgradeDev) {
            throw new \InvalidArgumentException('It is impossible to run with both upgrade and upgrade dev');
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

    public function upgradeDev() : bool
    {
        return $this->upgradeDev;
    }
}
