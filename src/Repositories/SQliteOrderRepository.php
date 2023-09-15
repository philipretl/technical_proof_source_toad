<?php

namespace Philipretl\TechnicalTestSourcetoad\Repositories;

use PDO;
use Philipretl\TechnicalTestSourcetoad\Models\OrderModel;

class SQliteOrderRepository implements Contracts\OrderRepository
{

    public function __construct(
        protected PDO $pdo
    ) {
    }

    public function create(
        float $tax,
        float $shipping_rate,
        float $sub_total,
        float $total,
        int $cart_id,
        int $customer_id,
        int $address_id
    ): OrderModel {

        $sql = 'INSERT INTO orders(
                  tax, 
                  shipping_rate, 
                  sub_total,
                  total,
                  cart_id,
                  customer_id,
                  address_id
                  ) '
            . 'VALUES(
                  :tax, 
                  :shipping_rate, 
                  :sub_total,
                  :total,
                  :cart_id,
                  :customer_id,
                  :address_id
                  )';

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':tax' => $tax,
            ':shipping_rate' => $shipping_rate,
            ':sub_total' => $sub_total,
            ':total' => $total,
            ':cart_id' => $cart_id,
            ':customer_id' => $customer_id,
            ':address_id' => $address_id
        ]);

        return new OrderModel(
            id: $this->pdo->lastInsertId(),
            tax: $tax,
            shipping_rate: $shipping_rate,
            sub_total: $sub_total,
            total: $total,
            cart_id: $cart_id,
            customer_id: $customer_id,
            address_id: $address_id
        );
    }

    public function getOrderByCustomer(int $customer_id): OrderModel
    {
        // TODO: Implement getOrderByCustomer() method.
    }

    public function getOrder(int $order_id): OrderModel
    {
        // TODO: Implement getOrder() method.
    }
}