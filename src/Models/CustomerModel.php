<?php

namespace Philipretl\TechnicalTestSourcetoad\Models;

class CustomerModel
{
    public function __construct(
        public int $id,
        public string $first_name,
        public string $last_name,
        public array $address_list = []
    ) {
    }

}