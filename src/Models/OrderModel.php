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
}