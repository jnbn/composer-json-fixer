<?php

namespace ComposerJsonFixer\Console;

use ComposerJsonFixer\Fixer;
use ComposerJsonFixer\Runner;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends \Symfony\Component\Console\Command\Command
{
    const NAME = 'composer-json-fixer';

    const DRY_RUN      = 'dry-run';
    const WITH_UPDATES = 'with-updates';

    const PATH = 'path';

    protected function configure()
    {
        $this->setName(self::NAME)
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
                self::PATH,
                InputArgument::OPTIONAL,
                'Path to directory containing "composer.json" file',
                \getcwd()
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            if ($input->hasOption(self::DRY_RUN) && $input->hasOption(self::WITH_UPDATES)) {
                throw new \Exception(\sprintf(
                    'It is impossible to run with both "--%s" and "--%s"',
                    self::DRY_RUN,
                    self::WITH_UPDATES
                ));
            }

            $fixer = new Runner($input->getArgument(self::PATH));

            $fixer->fix();

            if ($input->hasOption(self::DRY_RUN) && $fixer->hasAnythingBeenFixed()) {
                $output->writeln($fixer->diff());

                return 1;
            }

            if ($input->hasOption(self::WITH_UPDATES)) {
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
