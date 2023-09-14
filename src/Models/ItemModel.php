<?php

namespace Philipretl\TechnicalTestSourcetoad\Models;

class ItemModel
{
    public function __construct(
        public int $id,
        public string $name,
        public string $quantity,
        public float $price,
        public int $cart_id
    ) {
    }

    public function toArray():array
    {
        return [
          'id' => $this->id,
          'name' => $this->name,
          'quantity' => $this->quantity,
          'price' => $this->price,
          'cart_id' => $this->cart_id
        ];
    }
}