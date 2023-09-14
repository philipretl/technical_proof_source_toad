<?php

namespace Philipretl\TechnicalTestSourcetoad\Commands;

use Philipretl\TechnicalTestSourcetoad\DrawConsoleTable;
use Philipretl\TechnicalTestSourcetoad\SorterByKeys;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Philipretl\TechnicalTestSourcetoad\getUserValues;

class SorterDataCommand extends Command
{
    protected function configure()
    {
        $this->setName('second')
            ->setDescription('This order the data for the keys provided!');

    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $sorter_by_keys = new SorterByKeys();
        $console_drawer = new DrawConsoleTable();

        $table_dto = $console_drawer->buildTable(getUserValues());

        $sorter_by_keys->orderByKeys($table_dto->data, array());

        return Command::SUCCESS;
    }
}