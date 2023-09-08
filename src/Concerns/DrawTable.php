<?php

namespace Philipretl\TechnicalTestSourcetoad\Concerns;

use Symfony\Component\Console\Helper\Table;

interface DrawTable
{
    public function buildTable(array $values): Table;
}