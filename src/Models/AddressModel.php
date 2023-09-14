<?php

namespace Philipretl\TechnicalTestSourcetoad\Models;

class AddressModel
{
    public function __construct(
        public int $id,
        public string $line_1,
        public string $line_2,
        public string $city,
        public string $state,
        public string $zip,
        public int $customer_id
    ) {
    }

}