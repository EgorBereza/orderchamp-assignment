<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckOutController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

/**
*  Get all products
*/
Route::get('/home', [ProductController::class, 'index']);


/**
*  Get a single product
*  @param int $product  product id
*/
Route::get('/product/{product}', [ProductController::class, 'show']);


/**
*  Get cart content
*/
Route::get('/cart', [CartController::class, 'index']);


/**
* Add products to cart
* @param int $product  product id
* @param int $quantity quantity of products to be added to cart
*/
Route::post('/cart/{product}/{quantity}', [CartController::class, 'add']);


/**
* Remove products from cart
* @param int $product  product id
* @param int $quantity quantity of products to be removed from cart
*/
Route::delete('/cart/{product}/{quantity}', [CartController::class, 'remove']);


/**
*  Get user's checkout data.
*  This endpoint returns the cart data and if user is not visitor the user's data as well.
*  The above data should be used in the rendering of the checkout view.
*/
Route::get('/checkout', [CheckOutController::class, 'index']);


/**
*  Submits the checkout
*  This endpoint should be called in order to submit the checkout view.
* @param string $name.
* @param string $email
* @param string $country
* @param string $city
* @param string $address
* @param string $discountCode
*/
Route::post('/checkout', [CheckOutController::class, 'create']);



/**
*  Register a new user
*  This endpoint could be called during the checkout in order to register new user
* @param string $name.
* @param string $email
* @param string $password
* @param string $password_confirmation
* @param string $country
* @param string $city
* @param string $address
*/
Route::post('/checkout/register/user', [UserController::class, 'create']);


