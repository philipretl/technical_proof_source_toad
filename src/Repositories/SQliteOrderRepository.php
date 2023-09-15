<?php

namespace Philipretl\TechnicalTestSourcetoad\Repositories;

use Exception;
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

    /**
     * @return array<OrderModel>
     */
    public function getOrdersByCustomer(int $customer_id): array
    {
        $sql = 'SELECT * FROM orders'
            . ' WHERE customer_id = :customer_id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':customer_id' => $customer_id
        ]);

        $orders = [];

        while ($order = $stmt->fetchObject()) {
            $orders[] = new OrderModel(
                id: $order->id,
                tax: $order->tax,
                shipping_rate: $order->shipping_rate,
                sub_total: $order->sub_total,
                total: $order->total,
                cart_id: $order->cart_id,
                customer_id: $order->customer_id,
                address_id: $order->address_id
            );
        }

        if (empty($orders)) {
            throw new Exception("The user does not have orders.");
        }

        return $orders;
    }

    public function getOrder(int $order_id): OrderModel
    {
        // TODO: Implement getOrder() method.
    }
}