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

    public function fullName(){
        return $this->first_name . ' ' .$this->last_name;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
        ];
    }

}