<?php

namespace Philipretl\TechnicalTestSourcetoad;

use Philipretl\TechnicalTestSourcetoad\Concerns\DrawTable;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;

class DrawConsoleGraphTable implements DrawTable
{

    public function __construct(protected OutputInterface $output)
    {
    }

    public function buildTable(array $values): Table
    {
        $table = new Table($this->output);

        $formated_values = array();

        foreach ($values as $key => &$value) {
            foreach ($value as $internal_key => &$user_values) {
                if (is_array($user_values)) {
                    $user_values = 'array';
                }
            }

        }

        $table->setHeaders(array_keys($values[0]));
        $table->addRows($values);
        $table->setVertical();
        return $table;
    }
}