<?php

namespace Philipretl\TechnicalTestSourcetoad\Commands;

use Exception;
use Philipretl\TechnicalTestSourcetoad\DrawConsoleTable;
use Philipretl\TechnicalTestSourcetoad\SorterByKeys;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
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

        try {
            $ordered_table_dto = $sorter_by_keys->sortArray($table_dto->data, array('guest_id'));

            (new Table($output))
                ->setHeaders($ordered_table_dto->cells)
                ->setRows($ordered_table_dto->data)
                ->render();

        } catch (Exception $exception) {
            $output->writeln('<error>Some of the keys provided are not valid to sort the data.</error>');
            $output->writeln('<comment>The next table is the data unsorted.</comment>');
            // using named colors

            (new Table($output))
                ->setHeaders($table_dto->cells)
                ->setRows($table_dto->data)
                ->render();
        }

        return Command::SUCCESS;
    }
}