<?php

namespace App\Http\Controllers;

use App\Contracts\CartServiceInterface;
use App\Contracts\CheckOutServiceInterface;
use App\Contracts\OrderServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class CheckOutController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(protected CartServiceInterface $cartService,protected CheckOutServiceInterface $checkOutService,protected OrderServiceInterface $orderService)
    {
        //$this->middleware('auth');
    }


    /**
     * Returns data for the checkout view
     *
     * @return JsonResponse
     */
    public function index():JsonResponse
    {
        return response()->json($this->checkOutService->retriveViewData($this->cartService));
    }

       
    
    /**
     *  Handles the check out form submission.
     *
     * @return JsonResponse
     */
    public function create(Request $request):JsonResponse
    {
        $validationResult=$this->checkOutService->validateCheckOutData($request);
        if($validationResult['validation'])
        {
            return response()->json($this->checkOutService->checkOut($this->cartService,$this->orderService));
        }
        else
        {
            return response()->json($validationResult['error']);
        }
    }


}
