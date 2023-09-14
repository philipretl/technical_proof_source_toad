<?php

namespace Philipretl\TechnicalTestSourcetoad;

use Philipretl\TechnicalTestSourcetoad\Concerns\Sorter;

class SorterByKeys implements Sorter
{

    public function orderByKeys(array $data, array $keys_for_order): array
    {
        $sorted_data = $this->customQuicksort($data, array('guest_id'));
        print_r($sorted_data);
        return $sorted_data;
    }


    public function customQuicksort(array $array, array $sorter_keys)
    {
        $length = count($array);

        if ($length <= 1) {
            return $array;
        }

        $pivot_key = $sorter_keys[0];
        $pivot_value = $array[0][$pivot_key];

        $left = $right = array();

        foreach ($array as $key => $item) {

            $key_to_validate = $pivot_key;
            /**if(strpos($key, $pivot_key)){
             * print_r($pivot_key);
             * }**/
            print_r($item[$key_to_validate] . " : " . $pivot_value);
            if ($item[$key_to_validate] < $pivot_value) {
                $left[] = $item;
            } elseif ($item[$key_to_validate] > $pivot_value) {
                $right[] = $item;
            }

            if($item[$key_to_validate] == $pivot_value){
                print_r("are equals \n");
                print_r($pivot_value);
                if(count($sorter_keys) > 1){
                    array_shift($sorter_keys);
                }
            }
        }

        $left = $this->customQuicksort($left, $sorter_keys);
        $right = $this->customQuicksort($right, $sorter_keys);

        return array_merge($left, array($array[0]), $right);
    }
}