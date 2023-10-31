<?php

namespace App\Contracts;
use App\Models\Product;
use Illuminate\Http\Request;

interface CheckOutServiceInterface
{

    
    /**
     * returns data for the checkout view
     *
     * @return array
     */
    public function retriveViewData(CartServiceInterface $cartService);


    /**
     * performs the checkout
     *
     * @return array
     */
    public function checkOut(CartServiceInterface $cartService,OrderServiceInterface $orderService);

    /**
     * performs the validation on the data submited by user during the checkout
     *
     * @return array
     */
    public function validateCheckOutData(Request $request);




}