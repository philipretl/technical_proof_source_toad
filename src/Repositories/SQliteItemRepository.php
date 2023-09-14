<?php

namespace Philipretl\TechnicalTestSourcetoad\Repositories;

use Philipretl\TechnicalTestSourcetoad\Models\CustomerModel;
use Philipretl\TechnicalTestSourcetoad\Models\ItemModel;
use Philipretl\TechnicalTestSourcetoad\Repositories\Contracts\ItemRepository;
use Philipretl\TechnicalTestSourcetoad\Resources\ItemDataSource;

class SQliteItemRepository implements ItemRepository
{

    /**
     * @throws \Exception
     */
    public function getAllItemsByCart(int $cart_id): array
    {
        $items = array();

        $data_from_source = ItemDataSource::getItems();

        foreach ($data_from_source as $datum){
            if($datum['cart_id'] === $cart_id){
                $items[] = new ItemModel(
                    id: $datum['id'],
                    name: $datum['name'],
                    quantity: $datum['quantity'],
                    price: $datum['price'],
                    cart_id: $datum['cart_id']
                );
            }
        }

        if(empty($items)){
            throw new \Exception("The cart does not have items.");
        }

        return $items;
    }
}