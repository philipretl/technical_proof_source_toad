<?php

use Philipretl\TechnicalTestSourcetoad\Models\AddressModel;
use Philipretl\TechnicalTestSourcetoad\Services\ColombianShippingService;

test('it checks when the address is popayan that value returned is correct', function () {

    /**
     * Arrange
     */
    $address_spy = Mockery::spy(AddressModel::class)->makePartial();
    $address_spy->city = 'popayan';

    $shippin_service = new ColombianShippingService();
    /**
     * Act
     */
    $shipping_cost = $shippin_service->calculateShippingRate($address_spy);

    /**
     * Assert
     */

    expect($shipping_cost)->toEqual(10.5);
});

test('it checks when the address is bogota that value returned is correct', function () {

    /**
     * Arrange
     */
    $address_spy = Mockery::spy(AddressModel::class)->makePartial();
    $address_spy->city = 'bogota';

    $shippin_service = new ColombianShippingService();
    /**
     * Act
     */
    $shipping_cost = $shippin_service->calculateShippingRate($address_spy);

    /**
     * Assert
     */

    expect($shipping_cost)->toEqual(5.2);
});

test('it checks when the default price is returned', function () {

    /**
     * Arrange
     */
    $address_spy = Mockery::spy(AddressModel::class)->makePartial();
    $address_spy->city = 'new york';

    $shippin_service = new ColombianShippingService();
    /**
     * Act
     */
    $shipping_cost = $shippin_service->calculateShippingRate($address_spy);

    /**
     * Assert
     */

    expect($shipping_cost)->toEqual(20.0);
});

