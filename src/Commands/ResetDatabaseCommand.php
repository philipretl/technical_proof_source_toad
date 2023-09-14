<?php

namespace Philipretl\TechnicalTestSourcetoad\Commands;

use Philipretl\TechnicalTestSourcetoad\Config;
use Philipretl\TechnicalTestSourcetoad\Database\SQliteConnection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ResetDatabaseCommand extends Command
{

    protected function configure()
    {
        $this->setName('database:reset')
            ->setDescription('This command allows to reset the SQlite database');

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (file_exists(Config::PATH_TO_SQLITE_FILE)) {
            unlink(Config::PATH_TO_SQLITE_FILE);
            $output->writeln('<info>Database deleting.... </info>');
            $output->writeln('<info>Database delete completed. </info>');
            sleep(3);
        }

        $database = SQliteConnection::connect(Config::PATH_TO_SQLITE_FILE);

        if ($database->getPdo() != null) {
            $database->dropTables();
            $database->createTables();
            $output->writeln('');
            $output->writeln('<info>Database recreated. </info>');
        } else {
            $output->writeln('<error>Whoops, could not connect to the SQLite database! </error>');

        }

        return Command::SUCCESS;
    }
}