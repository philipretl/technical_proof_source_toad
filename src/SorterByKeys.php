<?php

namespace Philipretl\TechnicalTestSourcetoad;

use Philipretl\TechnicalTestSourcetoad\Concerns\Sorter;
use Philipretl\TechnicalTestSourcetoad\DTO\TableDTO;
use Philipretl\TechnicalTestSourcetoad\Traits\KeysTrait;

class SorterByKeys implements Sorter
{
    use KeysTrait;

    public function orderByKeys(array $data, array $keys_for_order): TableDTO
    {
        $sorted_data = $this->customQuicksort($data, $keys_for_order);
        return new TableDTO($this->getAllKeys($sorted_data), $sorted_data);
    }


    public function customQuicksort(array $array, array $sorter_keys, bool $are_equal = false)
    {
        $length = count($array);

        if ($length <= 1) {
            return $array;
        }

        $pivot_key = $sorter_keys[0];

        if(!array_key_exists($pivot_key, $array[0]) && $are_equal === false){
            throw new \Exception("The key does not exists");
        }

        $pivot_value = $array[0][$pivot_key];

        $left = $right = array();

        foreach ($array as $key => $item) {

            $key_to_validate = $pivot_key;

            /**if(strpos($key, $pivot_key)){
             * print_r($pivot_key);
             * }**/
            //print_r($item[$key_to_validate] . " : " . $pivot_value);
            if ($item[$key_to_validate] < $pivot_value) {
                $left[] = $item;
            } elseif ($item[$key_to_validate] > $pivot_value) {
                $right[] = $item;
            }

            if(($item[$key_to_validate] === $pivot_value) && $key !== 0){
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