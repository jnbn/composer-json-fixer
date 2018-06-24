<?php

declare(strict_types = 1);

namespace ComposerJsonFixer\Command;

use ComposerJsonFixer\Configuration;
use ComposerJsonFixer\RunnerFactory;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FixerCommand extends BaseCommand
{
    private const VERSION = '2.0.0';

    protected function configure() : void
    {
        $this
            ->addArgument('directory', InputArgument::OPTIONAL, 'Directory containing "composer.json" file', \getcwd())
            ->addOption('dry-run', 'd', InputOption::VALUE_NONE, 'Do not modify "composer.json", show only diff')
            ->addOption('upgrade', 'u', InputOption::VALUE_NONE, 'Upgrade dependencies with "composer require"')
            ->addOption('upgrade-dev', null, InputOption::VALUE_NONE, 'Upgrade dev dependencies with "composer require"');
    }

    protected function execute(InputInterface $input, OutputInterface $output) : int
    {
        $output->writeln(\sprintf('<info>%s</info> <comment>%s</comment>', $this->getName(), self::VERSION));

        try {
            $configuration = new Configuration(
                $input->getArgument('directory'),
                $input->getOption('dry-run'),
                $input->getOption('upgrade'),
                $input->getOption('upgrade-dev')
            );

            $fixer = RunnerFactory::create($configuration->directory());

            $fixer->fix();

            if ($configuration->dryRun() && $fixer->hasAnythingBeenFixed()) {
                $output->writeln($fixer->diff());

                return 1;
            }

            if ($configuration->upgrade() || $configuration->upgradeDev()) {
                $fixer->runUpdates($configuration->upgradeDev());
            }

            if ($fixer->hasAnythingBeenFixed()) {
                $fixer->save();
                $output->writeln('File "composer.json" was fixed successfully');

                return 1;
            }

            $output->writeln('There is nothing to fix');

            return 0;
        } catch (\Exception $exception) {
            $output->writeln(\sprintf('Exception was thrown: %s', $exception->getMessage()));

            return 2;
        }
    }
}
