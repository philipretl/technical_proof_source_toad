<?php

namespace Philipretl\TechnicalTestSourcetoad\Repositories;

use PDO;
use Philipretl\TechnicalTestSourcetoad\Models\CustomerModel;
use Philipretl\TechnicalTestSourcetoad\Repositories\Contracts\AddressRepository;

class SQliteCustomerRepository implements Contracts\CustomerRepository
{

    public function __construct(
        protected PDO $pdo
    ) {
    }

    public function create(string $first_name, string $last_name): CustomerModel
    {
        $sql = 'INSERT INTO customers(first_name, last_name) '
            . 'VALUES(:first_name, :last_name)';

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
        ]);

        return new CustomerModel(
            id: $this->pdo->lastInsertId(),
            first_name: $first_name,
            last_name: $last_name
        );
    }

    public function getAllCustomers(): array
    {
        $stmt = $this->pdo->query(
            'SELECT *' .
            'FROM customers'
        );

        $customers = [];

        while ($customer = $stmt->fetchObject()) {
            $customers[] = new CustomerModel(
                id: $customer->id,
                first_name: $customer->first_name,
                last_name: $customer->last_name
            );
        }
        return $customers;
    }
}