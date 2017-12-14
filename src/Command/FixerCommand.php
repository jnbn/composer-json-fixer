<?php

namespace ComposerJsonFixer\Command;

use ComposerJsonFixer\RunnerFactory;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FixerCommand extends BaseCommand
{
    const VERSION = '1.3.0';

    const DRY_RUN      = 'dry-run';
    const WITH_UPDATES = 'with-updates';

    const DIRECTORY = 'directory';

    protected function configure()
    {
        $this
            ->addOption(
                self::DRY_RUN,
                'd',
                InputOption::VALUE_NONE,
                'Do not modify "composer.json", show only diff'
            )
            ->addOption(
                self::WITH_UPDATES,
                'u',
                InputOption::VALUE_NONE,
                'Update dependencies with "composer require"'
            )
            ->addArgument(
                self::DIRECTORY,
                InputArgument::OPTIONAL,
                'Directory containing "composer.json" file',
                \getcwd()
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(\sprintf('<info>%s</info> <comment>%s</comment>', $this->getName(), self::VERSION));

        try {
            if ($input->getOption(self::DRY_RUN) === true && $input->getOption(self::WITH_UPDATES) === true) {
                throw new \Exception(\sprintf(
                    'It is impossible to run with both "--%s" and "--%s"',
                    self::DRY_RUN,
                    self::WITH_UPDATES
                ));
            }

            $fixer = RunnerFactory::create($input->getArgument(self::DIRECTORY));

            $fixer->fix();

            if ($input->getOption(self::DRY_RUN) === true && $fixer->hasAnythingBeenFixed()) {
                $output->writeln($fixer->diff());

                return 1;
            }

            if ($input->getOption(self::WITH_UPDATES) === true) {
                $fixer->runUpdates();
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
