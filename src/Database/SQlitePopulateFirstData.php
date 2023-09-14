<?php

namespace Philipretl\TechnicalTestSourcetoad\Database;

use PDO;
use Philipretl\TechnicalTestSourcetoad\Database\Contracts\PopulateFirstData;
use Philipretl\TechnicalTestSourcetoad\Repositories\Contracts\AddressRepository;
use Philipretl\TechnicalTestSourcetoad\Repositories\Contracts\CartRepository;
use Philipretl\TechnicalTestSourcetoad\Repositories\Contracts\CustomerRepository;
use Philipretl\TechnicalTestSourcetoad\Repositories\Contracts\ItemRepository;

class SQlitePopulateFirstData implements PopulateFirstData
{
    public function __construct(
        protected CustomerRepository $customer_repository,
        protected AddressRepository $address_repository,
        protected CartRepository $cart_repository,
        protected ItemRepository $item_repository
    ) {
    }

    public function insertFirstData(): void
    {
        $customer_1 = $this->customer_repository->create('Andres', 'Vega');
        $customer_2 = $this->customer_repository->create('Juan', 'Vega');

        $address_1 = $this->address_repository->create(
            line_1: 'Cra 17 55 N 45',
            line_2: 'Casa 25',
            city: 'popayan',
            state: 'cauca',
            zip: '190001',
            customer_id: $customer_1->id
        );

        $address_2 = $this->address_repository->create(
            line_1: 'Cra 109 # 76 - 34',
            line_2: 'Apto - 1211',
            city: 'bogota',
            state: 'cundinamarca',
            zip: '130001',
            customer_id: $customer_1->id
        );

        $cart_1 = $this->cart_repository->create(true, $customer_1->id);

        $this->item_repository->create(
            name: 'Milk',
            quantity: 2,
            price: 50,
            cart_id: $cart_1->id
        );

        $this->item_repository->create(
            name: 'Bread',
            quantity: 5,
            price: 2,
            cart_id: $cart_1->id
        );


    }
}