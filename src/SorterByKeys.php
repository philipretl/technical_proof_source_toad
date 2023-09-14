<?php

namespace Philipretl\TechnicalTestSourcetoad;

use Philipretl\TechnicalTestSourcetoad\Concerns\OrderTable;

class OrderTableConsole implements OrderTable
{

    public function orderByKeys(array $data, array $keys_for_order): array
    {
        $sorted_data = $this->quicksort($data);
        print_r($sorted_data);
        return $sorted_data;
    }


    public function quicksort(array $array)
    {
        $length = count($array);

        if ($length <= 1) {
            return $array;
        }

        $keys = array_keys($array);
        $pivot_key = $keys[0];

        $left = $right = array();

        foreach ($array as $key => $item){
            if($key < $pivot_key){
                $left[] = $item;
            }else {
                $right[] = $item;
            }
        }

        $left = $this->quickSort($left);
        $right = $this->quickSort($right);

        return array_merge($left, array($array[$pivot_key]), $right);
    }
}