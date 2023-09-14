<?php

namespace Philipretl\TechnicalTestSourcetoad\Concerns;

use Philipretl\TechnicalTestSourcetoad\DTO\TableDTO;

interface TableNormalizer
{
    public function normalize(array $values): TableDTO;
}