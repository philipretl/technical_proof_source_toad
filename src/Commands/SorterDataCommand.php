<?php

namespace Philipretl\TechnicalTestSourcetoad\Commands;

use Exception;
use Philipretl\TechnicalTestSourcetoad\Resources\UserDataSource;
use Philipretl\TechnicalTestSourcetoad\Utils\ConsoleTableNormalizer;
use Philipretl\TechnicalTestSourcetoad\Utils\SorterByKeys;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use function Laravel\Prompts\multiselect;

class SorterDataCommand extends Command
{
    protected function configure()
    {
        $this->setName('challenge:second')
            ->setDescription('This order the data for the keys provided!');

    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $keys_to_sort = multiselect(
            label: 'Select the keys that you want to filter. You can choosse any number',
            options: [
                'guest_id' => 'Guest id',
                'guest_type' => 'Guest type',
                'middle_name' => 'Middle name',
                'first_name' => 'First name',
                'last_name' => 'Last name',
                'gender' => 'Gender',
                'g_a.account_id' => 'Guest Account -> Account id',
                'g_a.account_limit' => 'Guest Account -> Account limit',
                'g_a.allow_charges' => 'Guest Account -> Allow charges',
                'g_a.status_id' =>  'Guest Account -> Status id',
                'g_b.booking_number' =>  'Guest Booking -> Status id',
                'g_b.end_time' =>  'Guest Booking -> End time',
                'g_b.is_checked_in' =>  'Guest Booking -> Checked in',
                'g_b.room_no' =>  'Guest Booking -> Room number',
                'g_b.ship_code' =>  'Guest Booking -> Ship code',
                'g_b.start_time' =>  'Guest Booking -> Start time',
            ],
            default: ['guest_id']
        );

        (new Table($output))
            ->setHeaders([
                'keys_to_order',
            ])
            ->addRow($keys_to_sort)
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
            $ordered_table_dto = $sorter_by_keys->sortArray(UserDataSource::getValues(), $keys_to_sort);

            $output->writeln('<info>Table of values: </info>');
            (new Table($output))
                ->setHeaders($ordered_table_dto->cells)
                ->setRows($ordered_table_dto->data)
                ->render();

        } catch (Exception $exception) {
            $output->writeln('<error>Some of the keys provided are not valid to sort the data.</error>');
            $output->writeln('<error>Keys for sort received: </error>');
            $output->writeln($keys_to_sort);
            return Command::INVALID;
        }

        return Command::SUCCESS;
    }
}