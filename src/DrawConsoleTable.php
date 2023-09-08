<?php

namespace Philipretl\TechnicalTestSourcetoad;

use Philipretl\TechnicalTestSourcetoad\Concerns\DrawTable;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;

class DrawConsoleTable implements DrawTable
{
    const EMPTY_ROW = "=========";

    public function __construct(protected OutputInterface $output)
    {
    }

    public function buildTable(array $values): Table
    {
        $table = new Table($this->output);

        $converted_table = array();

        $first_value_parsed = $this->convertToEasyKeys($values[0]);

        $headers = array();
        foreach ($values as $key => $parsed_value) {
            $processed_value = $this->convertToEasyKeys($parsed_value);
            array_push($converted_table, $processed_value);
        }

        $keys = $this->getAllKeys($converted_table);

        $table->setHeaders($keys);

        $this->fillIncompleteValues($converted_table, $keys);
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
        foreach ($all_keys as $key){
            foreach ($converted_table as $item) {
                //print_r("key: " . $key);
                if(!array_key_exists($key, $item)){
                    print_r('no existe:' . $key);
                    $item[$key] = self::EMPTY_ROW;
                }
                print_r($item);
            }
        }

    }

    public function convertToEasyKeys($matriz, $parent_key = ''): array
    {
        $result_array = array();

        foreach ($matriz as $key => $value) {
            $resumend_parent_key = ($parent_key ? $this->convertirString($parent_key) . '.' : '');
            $new_key = $resumend_parent_key . $key;
            if (is_array($value) === false) {
                $result_array[$new_key] = (is_null($value)) ? self::EMPTY_ROW : $value;
            } else {
                //$result_array[$key] = '->';
                $sub_table = $this->convertToEasyKeys($value[0], $new_key);
                $result_array = array_merge($result_array, $sub_table);
            }
        }

        return $result_array;

    }

    public function convertirString($cadena)
    {
        $palabras = explode('_', $cadena); // Divide la cadena en palabras usando el guión bajo como separador
        $resultado = '';

        foreach ($palabras as $palabra) {
            $resultado .= substr($palabra, 0, 1); // Agrega la primera letra de cada palabra
        }

        return $resultado;
    }

}