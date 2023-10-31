<?php

namespace App\Services;
use App\Contracts\CartServiceInterface;
use App\Models\Product;

class CartService implements CartServiceInterface
{
    protected array $cart=[];

    /**
     * Add a product to the cart.
     *
     * @param Product $product The product to add.
     * @param int $quantity The quantity to add.
     */
    public function addProductToCart(Product $product, int $quantity = 1)
    {
        $id = $product->id;

        if (isset($this->cart[$id])) 
        {
            $this->cart[$id]['quantity'] += $quantity;
        }
        else
        {
            $this->cart[$id] = [
                'product' => $product,
                'quantity' => $quantity,
            ];
        }
        return $this->saveCart();
    }

    /**
     * Remove a specific product quantity from the cart.
     *
     * @param Product $product The product to remove.
     * @param int $quantity The quantity to remove.
     */
    public function removeProductFromCart(Product $product, int $quantity = 1)
    {
        $id = $product->id;

        if (isset($this->cart[$id]))
        {
            if ($this->cart[$id]['quantity'] <= $quantity) 
            {
                unset($this->cart[$id]);
            }
            else
            {
                $this->cart[$id]['quantity'] -= $quantity;
            }
        }
        return $this->saveCart();
    }


    /**
     * Save the cart in the session.
     */
    public function saveCart()
    {
        session()->put('cart', serialize($this->cart));
        return $this;
    }

    /**
     * Load the cart from the session.
     */
    public function loadCart()
    {
        $serializedCart = session()->get('cart');
        if ($serializedCart)
        {
            $this->cart=unserialize($serializedCart);
        }
        else
        {
            $this->cart = [];
        }
        return $this;
    }

    /**
     * empty the cart in the session.
     */
    public function emptyCart()
    {
        $this->cart = [];
        session()->forget('cart');
        return $this;
    }
    
    
    /**
     * returns the cart
    */
    public function getCart()
    {
        return $this->cart;
    }


}
