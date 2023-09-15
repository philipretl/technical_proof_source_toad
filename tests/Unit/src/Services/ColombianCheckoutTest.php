<?php

use Philipretl\TechnicalTestSourcetoad\DTO\OrderPricesDTO;
use Philipretl\TechnicalTestSourcetoad\Models\AddressModel;
use Philipretl\TechnicalTestSourcetoad\Models\CartModel;
use Philipretl\TechnicalTestSourcetoad\Models\ItemModel;
use Philipretl\TechnicalTestSourcetoad\Models\OrderModel;
use Philipretl\TechnicalTestSourcetoad\Repositories\Contracts\CartRepository;
use Philipretl\TechnicalTestSourcetoad\Repositories\Contracts\OrderRepository;
use Philipretl\TechnicalTestSourcetoad\Services\ColombianCheckout;
use Philipretl\TechnicalTestSourcetoad\Services\Contracts\ShippingService;

test('it checks if calculate prices is correct', function () {
    /**
     * Arrange
     */

    $shipping_service_spy = Mockery::spy(ShippingService::class)->makePartial();
    $shipping_service_spy->shouldReceive('calculateShippingRate')->andReturn(20.0);
    $order_repository_spy = Mockery::spy(OrderRepository::class)->makePartial();
    $cart_repository_spy = Mockery::spy(CartRepository::class)->makePartial();

    $item_1 = Mockery::spy(ItemModel::class, [
        1,
        'Bread',
        2,
        2,
        1
    ]);

    $item_2 = Mockery::spy(ItemModel::class, [
        1,
        'Milk',
        1,
        10,
        1
    ]);

    $items_spy = [
        $item_1,
        $item_2
    ];

    $cart_spy = Mockery::spy(CartModel::class)->makePartial();
    $cart_spy->items = $items_spy;

    $address_spy = Mockery::spy(AddressModel::class);

    $checkout = new ColombianCheckout(
        $shipping_service_spy,
        $order_repository_spy,
        $cart_repository_spy
    );

    /**
     * Act
     */

    $order_prices_dto = $checkout->calculatePrices($cart_spy, $address_spy);

    /**
     * Assert
     */

    expect($order_prices_dto->shipping_price)->toEqual(20.0);
    expect($order_prices_dto->subtotal)->toEqual(14);
    expect($order_prices_dto->tax_price)->toEqual(14.98);
    expect($order_prices_dto->total)->toEqual(48.98);

    $shipping_service_spy->shouldHaveReceived('calculateShippingRate')->once();

});

test('it checks if the order is finished correctly', function () {
    /**
     * Arrange
     */


    $shipping_service_spy = Mockery::spy(ShippingService::class)->makePartial();
    $shipping_service_spy->shouldReceive('calculateShippingRate')->andReturn(20.0);
    $order_repository_spy = Mockery::spy(OrderRepository::class)->makePartial();
    $cart_repository_spy = Mockery::spy(CartRepository::class)->makePartial();

    $order_model_spy = Mockery::spy(OrderModel::class)->makePartial();
    $order_repository_spy->shouldReceive('create')->andReturn($order_model_spy);

    $cart_spy = Mockery::spy(CartModel::class)->makePartial();
    $cart_spy->id = 1;
    $cart_spy->customer_id = 1;

    $address_spy = Mockery::spy(AddressModel::class);
    $address_spy->id = 1;

    $order_prices_dto_spy = Mockery::spy(OrderPricesDTO::class)->makePartial();
    $order_prices_dto_spy->tax_price = 7.0;
    $order_prices_dto_spy->shipping_price = 10.0;
    $order_prices_dto_spy->subtotal = 100.0;
    $order_prices_dto_spy->total = 117.0;

    $checkout = new ColombianCheckout(
        $shipping_service_spy,
        $order_repository_spy,
        $cart_repository_spy
    );

    /**
     * Act
     */
    $order_model = $checkout->finishCheckoutProcess($cart_spy,$address_spy, $order_prices_dto_spy);

    /**
     * Assert
     */

    expect($order_model)->toBeInstanceOf(OrderModel::class);

    $order_repository_spy->shouldHaveReceived('create')->once();
    $cart_repository_spy->shouldHaveReceived('mutateCart')->once();

});