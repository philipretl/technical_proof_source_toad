<?php

namespace Philipretl\TechnicalTestSourcetoad\Models;

class CartModel
{
    public function __construct(
        public int $id,
        public bool $last_active,
        public int $customer_id,
        public array $items = []
    ) {
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'last_active' => $this->last_active,
            'customer_id' => $this->customer_id,
        ];
    }
}