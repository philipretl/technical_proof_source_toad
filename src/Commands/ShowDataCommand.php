<?php

namespace Philipretl\TechnicalTestSourcetoad\Commands;

use Philipretl\TechnicalTestSourcetoad\DrawConsoleGraphTable;
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
        $this->setName('first')
            ->setDescription('This prints the information on a table!')
            ->setHelp('Demonstration of custom commands created by Symfony Console component.');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $console_drawer = new DrawConsoleTable($output);
        $table_dto = $console_drawer->buildTable(getUserValues());

        $output->writeln('<info>This is the abreviature list: </info>');

        (new Table($output))
            ->setHeaders([
                'name',
                'short_name'
            ])->addRow([
                'guest_booking',
                'g_b'
            ])->addRow([
                'guest_account',
                'g_a'
            ])
            ->setVertical()
            ->render();

        $output->writeln('<info>Table of values: </info>');

        (new Table($output))
            ->setHeaders($table_dto->cells)
            ->setRows($table_dto->data)
            ->render();

        return Command::SUCCESS;
    }
}