<?php

namespace Philipretl\TechnicalTestSourcetoad\Repositories\Contracts;

use Philipretl\TechnicalTestSourcetoad\Models\ItemModel;

interface ItemRepository
{
    /**
     * @return array<ItemModel>
     */
    public function getAllItemsByCart(int $cart_id): array;
}