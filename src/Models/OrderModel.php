<?php

namespace Philipretl\TechnicalTestSourcetoad\Models;

class OrderModel
{
    public function __construct(
        public int $id,
        public float $tax,
        public float $shipping_rate,
        public float $sub_total,
        public float $total,
        public int $cart_id,
        public int $customer_id,
        public int $address_id
    ) {
    }


    public function toArray():array
    {
        return [
            'id' => $this->id,
            'tax' => $this->tax,
            'shipping_rate' => $this->shipping_rate,
            'subtotal' => $this->sub_total,
            'cart_id' => $this->cart_id,
            'customer_id' => $this->customer_id,
            'address_id' => $this->address_id
        ];
    }
}