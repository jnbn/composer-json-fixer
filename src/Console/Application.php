<?php

namespace ComposerJsonFixer\Console;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Application extends \Symfony\Component\Console\Application
{
    const VERSION = '1.2.2';

    public function __construct()
    {
        parent::__construct(Command::NAME, self::VERSION);
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        if (!$input->hasParameterOption(['--version', '-V'], true)) {
            $output->writeln(sprintf('<info>%s</info> <comment>%s</comment>', $this->getName(), $this->getVersion()));
        }

        try {
            return parent::doRun($input, $output);
        } catch (\Exception $e) {
            $output->writeln(sprintf("\n<error>%s</error>\n", $e->getMessage()));
            parent::doRun(new ArrayInput(['--help']), $output);

            return 2;
        }
    }

    /**
     * @return InputDefinition
     */
    public function getDefinition()
    {
        $definition = parent::getDefinition();
        $definition->setArguments();

        return $definition;
    }

    /**
     * @param InputInterface $input
     *
     * @return string
     */
    protected function getCommandName(InputInterface $input)
    {
        return Command::NAME;
    }

    /**
     * @return array
     */
    protected function getDefaultCommands()
    {
        return array_merge(parent::getDefaultCommands(), [new Command()]);
    }
}
