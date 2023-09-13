<?php

namespace Philipretl\TechnicalTestSourcetoad\Concerns;

use Philipretl\TechnicalTestSourcetoad\DTO\TableDTO;
use Symfony\Component\Console\Helper\Table;

interface DrawTable
{
    public function buildTable(array $values): TableDTO;
}