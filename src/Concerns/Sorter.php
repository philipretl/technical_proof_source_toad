<?php

namespace Philipretl\TechnicalTestSourcetoad\Concerns;

use Philipretl\TechnicalTestSourcetoad\DTO\TableDTO;

interface Sorter
{
    public function orderByKeys(array $data, array $keys_for_order): TableDTO;
}