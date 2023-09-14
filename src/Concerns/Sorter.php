<?php

namespace Philipretl\TechnicalTestSourcetoad\Concerns;

interface Sorter
{
    public function orderByKeys(array $data, array $keys_for_order): array;
}