<?php

namespace Philipretl\TechnicalTestSourcetoad\Repositories\Contracts;

use Philipretl\TechnicalTestSourcetoad\Models\AddressModel;

interface AddressRepository
{
    /**
     * @return array<AddressModel>
     */
    public function create(
        string $line_1,
        string $line_2,
        string $city,
        string $state,
        string $zip,
        int $customer_id
    ): AddressModel;
}