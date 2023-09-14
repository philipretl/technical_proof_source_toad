<?php

namespace Philipretl\TechnicalTestSourcetoad\Commands;

use Philipretl\TechnicalTestSourcetoad\Config;
use Philipretl\TechnicalTestSourcetoad\ConsoleTableNormalizer;
use Philipretl\TechnicalTestSourcetoad\Database\SQliteConnection;
use Philipretl\TechnicalTestSourcetoad\Repositories\SQliteCustomerRepository;
use Philipretl\TechnicalTestSourcetoad\Resources\UserDataSource;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EcommerceCommand extends Command
{

    protected function configure()
    {
        $this->setName('challlenge:third')
            ->setDescription('This start the ecommerce challenge');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $database = SQliteConnection::connect(Config::PATH_TO_SQLITE_FILE);

        if ($database->getPdo() != null) {

            $customer_repository = new SQliteCustomerRepository($database->getPdo());
            $customers = $customer_repository->getAllCustomers();
            $propierties = get_object_vars($customers[0]);
            $keys = array_keys($propierties);

            $table = new Table($output);
            $table->setHeaders($keys);

            foreach ($customers as $customer) {
                $table->addRow($customer->toArray());
            }

            $table->render();

        } else {
            $output->writeln('<error>Whoops, could not connect to the SQLite database! </error>');

        }

        return Command::SUCCESS;
    }
}