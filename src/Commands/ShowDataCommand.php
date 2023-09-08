<?php

namespace Philipretl\TechnicalTestSourcetoad\Commands;

use Philipretl\TechnicalTestSourcetoad\DrawConsoleTable;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Philipretl\TechnicalTestSourcetoad\getUserValues;

class ShowDataCommand extends Command
{

    protected function configure()
    {
        $this->setName('hello-world')
            ->setDescription('Prints Hello-World!')
            ->setHelp('Demonstration of custom commands created by Symfony Console component.')
            ->addArgument('username', InputArgument::REQUIRED, 'Pass the username.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $console_drawer = new DrawConsoleTable($output);

        $table = $console_drawer->buildTable(getUserValues());
        $table->render();

        return Command::SUCCESS;
    }
}