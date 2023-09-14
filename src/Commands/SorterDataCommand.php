<?php

namespace Philipretl\TechnicalTestSourcetoad\Commands;

use Exception;
use Philipretl\TechnicalTestSourcetoad\ConsoleTableNormalizer;
use Philipretl\TechnicalTestSourcetoad\Resources\UserDataSource;
use Philipretl\TechnicalTestSourcetoad\SorterByKeys;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Philipretl\TechnicalTestSourcetoad\getUserValues;

class SorterDataCommand extends Command
{
    protected function configure()
    {
        $this->setName('challenge:second')
            ->addOption(
                'keys',
                'k',
                InputArgument::IS_ARRAY | InputArgument::REQUIRED,
                'Input the keys to search (separate multiple keys with a space)'
            )
            ->setDescription('This order the data for the keys provided!');

    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $inputs = $input->getOption('keys');

        $keys_to_order = explode(',', $inputs ?? 'empty');

        (new Table($output))
            ->setHeaders([
                'keys_to_order',
            ])
            ->addRow($keys_to_order)
            ->setVertical()
            ->render();

        (new Table($output))
            ->setHeaders([
                'name',
                'short_name'
            ])
            ->addRow([
                'guest_booking',
                'g_b'
            ])
            ->addRow([
                'guest_account',
                'g_a'
            ])
            ->setVertical()
            ->render();

        $sorter_by_keys = new SorterByKeys(new ConsoleTableNormalizer());

        try {
            $ordered_table_dto = $sorter_by_keys->sortArray(UserDataSource::getValues(), $keys_to_order);

            $output->writeln('<info>Table of values: </info>');
            (new Table($output))
                ->setHeaders($ordered_table_dto->cells)
                ->setRows($ordered_table_dto->data)
                ->render();

        } catch (Exception $exception) {
            $output->writeln('<error>Some of the keys provided are not valid to sort the data.</error>');
            $output->writeln('<error>Keys for sort received: </error>');
            $output->writeln($keys_to_order);
            return Command::INVALID;
        }

        return Command::SUCCESS;
    }
}