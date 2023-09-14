<?php

namespace Philipretl\TechnicalTestSourcetoad;

use Philipretl\TechnicalTestSourcetoad\Concerns\Sorter;
use Philipretl\TechnicalTestSourcetoad\Concerns\TableNormalizer;
use Philipretl\TechnicalTestSourcetoad\DTO\TableDTO;
use Philipretl\TechnicalTestSourcetoad\Traits\KeysTrait;

class SorterByKeys implements Sorter
{
    use KeysTrait;

    public function __construct(protected TableNormalizer $table_normalizer){}

    public function sortArray(array $data, array $keys_for_order): TableDTO
    {
        $table_dto = $this->table_normalizer->normalize($data);

        $sorted_data = $this->customQuicksort($table_dto->data, $keys_for_order);

        return new TableDTO($this->getAllKeys($sorted_data), $sorted_data);
    }


    public function customQuicksort(array $array, array $sorter_keys)
    {
        $length = count($array);

        if ($length <= 1) {
            return $array;
        }

        $pivot_key = $this->getCompletedKey($array[0], $sorter_keys[0]);

        $pivot_value = $array[0][$pivot_key];

        $left = $right = array();

        foreach ($array as $key => $current_item) {

            if ($current_item[$pivot_key] < $pivot_value) {
                $left[] = $current_item;
            } elseif ($current_item[$pivot_key] > $pivot_value) {
                $right[] = $current_item;
            }

            if (($current_item[$pivot_key] === $pivot_value) && $key !== 0) {

                if (count($sorter_keys) > 1) {
                    $this->tiebreaker($array[0], $current_item, $sorter_keys, $left, $right);
                } else {
                    $right[] = $current_item;
                }
            }
        }

        $left = $this->customQuicksort($left, $sorter_keys);
        $right = $this->customQuicksort($right, $sorter_keys);

        return array_merge($left, array($array[0]), $right);
    }

    /**
     * @throws \Exception
     */
    public function getCompletedKey(array $array, string $sorter_key): string
    {
        foreach ($array as $key => $item) {
            if (strpos($key, $sorter_key) !== false) {
                return $key;
            }
        }

        throw new \Exception("The key does not exists");

    }

    public function tiebreaker(array $pivot, $current_item, array $sorter_keys, array &$left, array &$right)
    {
        array_shift($sorter_keys);

        $tiebreaker = $this->customQuicksort(array($pivot, $current_item), $sorter_keys);
        $tiebreaker_key = $this->getCompletedKey($current_item, $sorter_keys[0]);

        if ($tiebreaker[0][$tiebreaker_key] === $pivot[$tiebreaker_key]) {
            $right[] = $tiebreaker[1];
        } else {
            $left[] = $tiebreaker[0];
        }
    }
}