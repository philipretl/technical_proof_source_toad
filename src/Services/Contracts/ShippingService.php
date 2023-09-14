<?php

namespace Philipretl\TechnicalTestSourcetoad\Services\Contracts;

use Philipretl\TechnicalTestSourcetoad\Models\AddressModel;

interface ShippingService
{
    public function calculateShippingRate(AddressModel $address): float;
}