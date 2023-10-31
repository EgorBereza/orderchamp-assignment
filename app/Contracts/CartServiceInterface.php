<?php

namespace App\Contracts;

use App\Models\Product;

interface CartServiceInterface
{
    /**
     * Add a product to the cart.
     *
     * @param Product $product The product to add.
     * @param int $quantity The quantity to add.
     * @return self
     */
    public function addProductToCart(Product $product, int $quantity = 1);

    /**
     * Remove a specific product quantity from the cart.
     *
     * @param Product $product The product to remove.
     * @param int $quantity The quantity to remove.
     * @return self
     */
    public function removeProductFromCart(Product $product, int $quantity = 1);

    /**
     * Get the items in the cart.
     *
     * @return array
     */
    public function getCart();

    /**
     * Save the cart in the session.
     * @return self
     */
    public function saveCart();

    /**
     *  oad the cart from the session.
     * @return self
     */
    public function loadCart();


    /**
     * empty the cart in the session.
     */
    public function emptyCart();


}
