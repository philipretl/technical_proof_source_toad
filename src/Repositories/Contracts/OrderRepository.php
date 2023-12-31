<?php

namespace Philipretl\TechnicalTestSourcetoad\Repositories\Contracts;

use Philipretl\TechnicalTestSourcetoad\Models\OrderModel;

interface OrderRepository
{
    public function getOrder(int $order_id): OrderModel;

    public function getOrdersByCustomer(int $customer_id): array;

    public function create(
        float $tax,
        float $shipping_rate,
        float $sub_total,
        float $total,
        int $cart_id,
        int $customer_id,
        int $address_id
    ): OrderModel;
}