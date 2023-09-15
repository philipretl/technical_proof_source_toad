<?php

namespace Philipretl\TechnicalTestSourcetoad\Services;

use Philipretl\TechnicalTestSourcetoad\DTO\OrderPricesDTO;
use Philipretl\TechnicalTestSourcetoad\Models\AddressModel;
use Philipretl\TechnicalTestSourcetoad\Models\CartModel;
use Philipretl\TechnicalTestSourcetoad\Models\OrderModel;
use Philipretl\TechnicalTestSourcetoad\Repositories\Contracts\CartRepository;
use Philipretl\TechnicalTestSourcetoad\Repositories\Contracts\OrderRepository;
use Philipretl\TechnicalTestSourcetoad\Services\Contracts\Checkout;
use Philipretl\TechnicalTestSourcetoad\Services\Contracts\ShippingService;

class ColombianCheckout implements Checkout
{
    public function __construct(
        protected ShippingService $shipping_service,
        protected OrderRepository $order_repository,
        protected CartRepository $cart_repository,
        protected float $percentage_tax = 7.0
    ) {
    }

    public function calculatePrices(CartModel $cart, AddressModel $address): OrderPricesDTO
    {
        $shipping_cost = $this->shipping_service->calculateShippingRate($address);

        $sub_total = 0;

        foreach ($cart->items as $item) {
            $sub_total += $item->quantity * $item->price;
        }

        $sub_total = round($sub_total, 2);

        $tax_price = (($sub_total * $this->percentage_tax) / 100) + $sub_total;
        $tax_price = round($tax_price, 2);

        $total = round($sub_total + $shipping_cost + $tax_price, 2);

        return new OrderPricesDTO(
            subtotal: $sub_total,
            tax_price: $tax_price,
            shipping_price: $shipping_cost,
            total: $total
        );
    }

    public function finishCheckoutProcess(CartModel $cart, AddressModel $address, OrderPricesDTO $order_prices_dto): OrderModel
    {

        $order_model = $this->order_repository->create(
            tax: $order_prices_dto->tax_price,
            shipping_rate: $order_prices_dto->shipping_price,
            sub_total: $order_prices_dto->subtotal,
            total: $order_prices_dto->total,
            cart_id: $cart->id,
            customer_id: $cart->customer_id,
            address_id: $address->id
        );

        $this->cart_repository->mutateCart($cart->id);

        return $order_model;
    }


}
