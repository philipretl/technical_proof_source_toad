<?php

namespace Philipretl\TechnicalTestSourcetoad\Repositories\Contracts;

use Philipretl\TechnicalTestSourcetoad\Models\ItemModel;

interface ItemRepository
{
    /**
     * @return array<ItemModel>
     */
    public function getAllItemsByCart(int $cart_id): array;

    public function create(string $name, int $quantity, float $price, int $cart_id): ItemModel;
}