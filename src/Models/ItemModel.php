<?php

namespace Philipretl\TechnicalTestSourcetoad\Models;

class ItemModel
{
    public function __construct(
        public int $id,
        public float $total_price,
        public int $cart_id
    ) {
    }

}