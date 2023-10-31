<?php

namespace App\Http\Controllers;

use App\Contracts\CartServiceInterface;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class CartController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CartServiceInterface $cartService)
    {
        //$this->middleware('auth');
    }


    /**
     * Returns carts content.
     *
     * @return JsonResponse
     */
    public function index():JsonResponse
    {
        return response()->json($this->cartService->loadCart()->getCart());
    }

    /**
     * Remove product from cart.
     *
     * @return JsonResponse
     */
    function remove(Product $product,int $quantity):JsonResponse
    {
        return response()->json($this->cartService->loadCart()->removeProductFromCart($product,$quantity)->getCart());
    }

  
    /**
     * Add product to cart.
     *
     * @return JsonResponse
     */
    public function add(Product $product,int $quantity):JsonResponse
    {
        return response()->json($this->cartService->loadCart()->addProductToCart($product,$quantity)->getCart());
    }

}
