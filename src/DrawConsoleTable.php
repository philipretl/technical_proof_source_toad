<?php

namespace Philipretl\TechnicalTestSourcetoad;

use Philipretl\TechnicalTestSourcetoad\Concerns\DrawTable;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;

class DrawConsoleTable implements DrawTable
{
    const EMPTY_ROW = "------------";

    public function __construct(protected OutputInterface $output)
    {
    }

    public function buildTable(array $values): Table
    {
        $table = new Table($this->output);

        $converted_table = array();


        foreach ($values as $key => $parsed_value) {
            $processed_value = $this->convertToEasyKeys($parsed_value);
            array_push($converted_table, $processed_value);
        }

        $keys = $this->getAllKeys($converted_table);
        $this->fillIncompleteValues($converted_table, $keys);
        print_r($converted_table);
        $this->orderKeys($converted_table);

        ksort($keys);
        $table->setHeaders($keys);

        $table->addRows($converted_table);

        return $table;
    }

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