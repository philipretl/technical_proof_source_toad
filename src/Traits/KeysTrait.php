<?php

namespace Philipretl\TechnicalTestSourcetoad\Traits;

trait KeysTrait
{
    public function getAllKeys(array $matrix): array
    {
        $keys = array();
        foreach ($matrix as $item) {
            foreach ($item as $key => $value) {
                $keys[$key] = $key;
            }
        }
        return array_unique($keys);
    }
}