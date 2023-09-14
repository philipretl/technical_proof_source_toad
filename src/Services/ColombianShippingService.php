<?php

namespace Philipretl\TechnicalTestSourcetoad\Services;

use Philipretl\TechnicalTestSourcetoad\Models\AddressModel;

class ColombianShippingService implements Contracts\ShippingService
{

    public function calculateShippingRate(AddressModel $address): float
    {
        return match ($address->city){
            'popayan' => 10.5,
            'bogota' => 5.2,
            default => 20.0
        };
    }
}