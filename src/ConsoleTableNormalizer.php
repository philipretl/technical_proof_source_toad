<?php

namespace Philipretl\TechnicalTestSourcetoad;

use Philipretl\TechnicalTestSourcetoad\Concerns\TableNormalizer;
use Philipretl\TechnicalTestSourcetoad\DTO\TableDTO;
use Philipretl\TechnicalTestSourcetoad\Traits\KeysTrait;

class ConsoleTableNormalizer implements TableNormalizer
{
    use KeysTrait;

    const EMPTY_ROW = "<empty>";

    public function __construct(){}

    public function normalize(array $values): TableDTO
    {
        $converted_table = array();

        foreach ($values as $key => $parsed_value) {
            $processed_value = $this->convertToEasyKeys($parsed_value);
            array_push($converted_table, $processed_value);
        }

        $keys = $this->getAllKeys($converted_table);
        $this->fillIncompleteValues($converted_table, $keys);

        $this->orderKeys($converted_table);
        ksort($keys);

        return new TableDTO($keys, $converted_table);
    }

    public function fillIncompleteValues(array &$converted_table, array $all_keys): void
    {
        foreach ($all_keys as $key) {
            foreach ($converted_table as &$item) {
                if (!array_key_exists($key, $item)) {
                    $item[$key] = self::EMPTY_ROW;
                }
            }
        }

    }

    public function orderKeys(array &$values): void
    {
        foreach ($values as &$value) {
            ksort($value);
        }
    }

    public function convertToEasyKeys($matriz, $parent_key = ''): array
    {
        $result_array = array();

        foreach ($matriz as $key => $value) {

            $resumed_parent_key = ($parent_key ? $this->cutKey($parent_key) . '.' : '');
            $new_key = $resumed_parent_key . $key;

            if (is_array($value)) {
                $sub_table = $this->convertToEasyKeys($value[0], $new_key);
                $result_array = array_merge($result_array, $sub_table);
            } else {
                $result_array[$new_key] = (is_null($value)) ? self::EMPTY_ROW : $value;
            }
        }

        return $result_array;

    }

    public function cutKey($cadena): string
    {
        $pattern = '/([a-z])[a-z]*_([a-z])[a-z]*/i';

        return preg_replace_callback($pattern, function ($matches) {
            return $matches[1] . '_' . $matches[2];
        }, $cadena);
    }

}