<?php

namespace Philipretl\TechnicalTestSourcetoad\Repositories;

use Exception;
use PDO;
use Philipretl\TechnicalTestSourcetoad\Models\AddressModel;

class SQliteAddressRepository implements Contracts\AddressRepository
{

    public function __construct(protected PDO $pdo)
    {
    }

    public function create(
        string $line_1,
        string $line_2,
        string $city,
        string $state,
        string $zip,
        int $customer_id
    ): AddressModel {
        $sql = 'INSERT INTO addresses(line_1, line_2, city, state, zip, customer_id) '
            . 'VALUES(:line_1, :line_2, :city, :state, :zip, :customer_id)';

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':line_1' => $line_1,
            ':line_2' => $line_2,
            ':city' => $city,
            ':state' => $state,
            ':zip' => $zip,
            ':customer_id' => $customer_id
        ]);

        return new AddressModel(
            id: $this->pdo->lastInsertId(),
            line_1: $line_1,
            line_2: $line_2,
            city: $city,
            state: $state,
            zip: $zip,
            customer_id: $customer_id
        );

    }

    public function getAddressByCustomer(int $customer_id): array
    {
        $sql = 'SELECT * FROM addresses'
            . ' WHERE customer_id = :customer_id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':customer_id' => $customer_id
        ]);

        $addresses = [];

        while ($address = $stmt->fetchObject()) {
            $addresses[] = new AddressModel(
                id: $address->id,
                line_1: $address->line_1,
                line_2: $address->line_2,
                city: $address->city,
                state: $address->state,
                zip: $address->zip,
                customer_id: $address->customer_id
            );
        }

        if (empty($addresses)) {
            throw new Exception("The user does not have addresses currently.");
        }

        return $addresses;
    }
}