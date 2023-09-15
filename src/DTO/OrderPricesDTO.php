<?php

namespace Philipretl\TechnicalTestSourcetoad\DTO;

class OrderPricesDTO
{
    public function __construct(
        public float $subtotal,
        public float $tax_price,
        public float $shipping_price,
        public float $total
    ) {
    }

    public function toArray()
    {
        return [
            'subtotal' => $this->subtotal,
            'tax_price' => $this->tax_price,
            'shipping_price' => $this->shipping_price,
            'total' => $this->total
        ];
    }

}