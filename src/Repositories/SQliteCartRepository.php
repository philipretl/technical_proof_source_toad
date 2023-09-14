<?php

namespace Philipretl\TechnicalTestSourcetoad\Repositories;

use Exception;
use PDO;
use Philipretl\TechnicalTestSourcetoad\Models\CartModel;
use Philipretl\TechnicalTestSourcetoad\Models\CustomerModel;
use Philipretl\TechnicalTestSourcetoad\Repositories\Contracts\ItemRepository;
use Philipretl\TechnicalTestSourcetoad\Resources\CartDataSource;

class SQliteCartRepository implements Contracts\CartRepository
{
    public function __construct(
        protected PDO $pdo
    ) {

    }


    public function create(bool $last_active, int $customer_id): CartModel
    {
        $sql = 'INSERT INTO carts(last_active, customer_id) '
            . 'VALUES(:last_active, :customer_id)';

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':last_active' => $last_active,
            ':customer_id' => $customer_id,
        ]);

        return new CartModel(
            id: $this->pdo->lastInsertId(),
            last_active: $last_active,
            customer_id: $customer_id
        );
    }

    public function getCartByCustomer(int $customer_id): CartModel
    {
        $sql = 'SELECT * FROM carts'
            . ' WHERE customer_id = :customer_id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':customer_id' => $customer_id
        ]);

        $carts = [];

        while ($cart = $stmt->fetchObject()) {
            $carts[] = new CartModel(
                id: $cart->id,
                last_active: (bool) $cart->last_active,
                customer_id: $cart->customer_id
            );
        }

        if(empty($carts)){
            throw new Exception("The user does not have carts currently.");
        }

        return $carts[0];
    }
}