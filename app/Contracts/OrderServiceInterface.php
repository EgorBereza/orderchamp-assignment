<?php

namespace App\Contracts;

interface OrderServiceInterface
{

     /**
     * Create a new order.
     *
     * @param array $cart
     * @param array $checkoutData
     * @return Order|array
     */
    public function createOrder(array $cart,array $checkoutData);

}
