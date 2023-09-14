<?php

namespace Philipretl\TechnicalTestSourcetoad\Repositories;

use PDO;
use Philipretl\TechnicalTestSourcetoad\Models\CustomerModel;
use Philipretl\TechnicalTestSourcetoad\Models\ItemModel;
use Philipretl\TechnicalTestSourcetoad\Repositories\Contracts\ItemRepository;
use Philipretl\TechnicalTestSourcetoad\Resources\ItemDataSource;

class SQliteItemRepository implements ItemRepository
{

    public function __construct(
        protected PDO $pdo
    ) {

    }

    public function create(string $name, int $quantity, float $price, int $cart_id): ItemModel
    {
        $sql = 'INSERT INTO items(name, quantity, price, cart_id) '
            . 'VALUES(:name, :quantity, :price, :cart_id)';

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':name' => $name,
            ':quantity' => $quantity,
            ':price' => $price,
            ':cart_id' => $cart_id
        ]);

        return new ItemModel(
            id: $this->pdo->lastInsertId(),
            name: $name,
            quantity: $quantity,
            price: $price,
            cart_id: $cart_id
        );
    }

    /**
     * @throws \Exception
     */
    public function getAllItemsByCart(int $cart_id): array
    {
        $sql = 'SELECT * FROM items'
            . ' WHERE cart_id = :cart_id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':cart_id' => $cart_id
        ]);

        $items = [];

        while ($item = $stmt->fetchObject()) {
            $items[] = new ItemModel(
                id: $item->id,
                name: $item->name,
                quantity: $item->quantity,
                price: $item->price,
                cart_id: $item->cart_id
            );
        }


        if (empty($items)) {
            throw new \Exception("The cart does not have items.");
        }

        return $items;
    }
}