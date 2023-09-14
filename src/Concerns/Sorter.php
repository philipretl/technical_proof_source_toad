<?php

namespace Philipretl\TechnicalTestSourcetoad\Concerns;

interface OrderTable
{
    public function orderByKeys(array $data, array $keys_for_order): array;
}