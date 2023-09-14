<?php

namespace Philipretl\TechnicalTestSourcetoad\Commands;

use Philipretl\TechnicalTestSourcetoad\Config;
use Philipretl\TechnicalTestSourcetoad\ConsoleTableNormalizer;
use Philipretl\TechnicalTestSourcetoad\Database\InsertFirstData;
use Philipretl\TechnicalTestSourcetoad\Database\SQliteConnection;
use Philipretl\TechnicalTestSourcetoad\Repositories\SQliteAddressRepository;
use Philipretl\TechnicalTestSourcetoad\Repositories\SQliteCartRepository;
use Philipretl\TechnicalTestSourcetoad\Repositories\SQliteCustomerRepository;
use Philipretl\TechnicalTestSourcetoad\Resources\UserDataSource;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class FirstTimeDatabaseCommand extends Command
{

    protected function configure()
    {
        $this->setName('database:first-time')
            ->setDescription('The ecommerce site!');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $database = SQliteConnection::connect(Config::PATH_TO_SQLITE_FILE);

        if ($database->getPdo() != null) {
            $database->createTables();

            $customer_repository = new SQliteCustomerRepository($database->getPdo());
            $address_repository = new SQliteAddressRepository($database->getPdo());
            $cart_repository = new SQliteCartRepository($database->getPdo());

            $founder = new InsertFirstData(
                $customer_repository,
                $address_repository,
                $cart_repository
            );

            $founder->insertFirstData();

            $output->writeln('<info>Database configuration finished. </info>');
        } else {
            $output->writeln('<error>Whoops, could not connect to the SQLite database! </error>');

        }
        return Command::SUCCESS;
    }
}