<?php

namespace Philipretl\TechnicalTestSourcetoad\Repositories\Contracts;

use Philipretl\TechnicalTestSourcetoad\Models\CartModel;

interface CartRepository
{
    public function create(bool $last_active, int $customer_id): CartModel;

    public function mutateCart(int $cart_id): void;
}