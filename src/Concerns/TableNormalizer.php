<?php

namespace Philipretl\TechnicalTestSourcetoad\Concerns;

use Philipretl\TechnicalTestSourcetoad\DTO\TableDTO;
use Symfony\Component\Console\Helper\Table;

interface TableNormalizer
{
    public function normalize(array $values): TableDTO;
}