<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

  
    /**
     * Show all products.
     *
     * @return JsonResponse
     */
    public function index():JsonResponse
    {
        return response()->json(Product::all());
    }

    /**
     * Show single product.
     *
     * @return JsonResponse
     */
    public function show(Product $product):JsonResponse
    {
        return response()->json($product);
    }

    
}
