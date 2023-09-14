<?php

namespace Philipretl\TechnicalTestSourcetoad\Repositories;

use Philipretl\TechnicalTestSourcetoad\Models\OrderModel;

class SQliteOrderRepository implements Contracts\OrderRepository
{

    public function getOrder(int $order_id): OrderModel
    {
        // TODO: Implement getOrder() method.
    }

    public function getOrderByCustomer(int $customer_id): OrderModel
    {
        // TODO: Implement getOrderByCustomer() method.
    }
}