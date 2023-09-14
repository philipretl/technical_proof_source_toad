<?php

namespace Philipretl\TechnicalTestSourcetoad\Repositories\Contracts;

use Philipretl\TechnicalTestSourcetoad\Models\OrderModel;

interface OrderRepository
{
    public function getOrder(int $order_id): OrderModel;

    public function getOrderByCustomer(int $customer_id): OrderModel;
}