<?php

namespace Philipretl\TechnicalTestSourcetoad\Services\Contracts;

use Philipretl\TechnicalTestSourcetoad\DTO\OrderPricesDTO;
use Philipretl\TechnicalTestSourcetoad\Models\AddressModel;
use Philipretl\TechnicalTestSourcetoad\Models\CartModel;
use Philipretl\TechnicalTestSourcetoad\Models\OrderModel;

interface Checkout
{
    public function calculatePrices(CartModel $cart, AddressModel $address): OrderPricesDTO;

    public function finishCheckoutProcess(CartModel $cart, AddressModel $address, OrderPricesDTO $order_prices_dto): OrderModel;

}