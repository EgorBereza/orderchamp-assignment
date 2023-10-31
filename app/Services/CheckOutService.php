<?php

namespace App\Services;

use App\Contracts\CartServiceInterface;
use App\Contracts\CheckOutServiceInterface;
use App\Contracts\OrderServiceInterface;
use App\Models\DiscountCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Order;

class CheckOutService implements CheckOutServiceInterface
{
    protected array $cart=[];
    protected array $checkoutData=[];
    

    /**
     * Returns data for the checkout view
     * if user then return the users previously stored information for review and the cart data
     * if guest then return only the cart data
     *
     * @return array
     */
    public function retriveViewData(CartServiceInterface $cartService)
    {
        $userData = [];
        if (Auth::check())
        {
            $user = Auth::user();
    
            $userData = [
                'name' => $user->name,
                'email' => $user->email,
                'country' => $user->country,
                'city' => $user->city,
                'address' => $user->address,
            ];
        }
        $cartData=$cartService->loadCart()->getCart();
        return ['userData'=>$userData,'cartData'=>$cartData]; 
    }


    /**
     * Performs the checkout
     *
     * @return Order|array
     */
    public function checkOut(CartServiceInterface $cartService,OrderServiceInterface $orderService)
    {
        $cart=$cartService->loadCart()->getCart();
        $order=$orderService->createOrder($cart,$this->checkoutData);
        if($order instanceof Order)
        {
            $cartService->emptyCart();
        }
        return $order;
    }


    /**
     * Performs the validation on the data submited by user during the checkout
     *
     * @return array
     */
    public function validateCheckOutData(Request $request)
    {
        $validator = $this->createCheckoutValidator($request);
        if ($validator->fails())
        {
            return ['validation'=>false,'error' => $validator->errors()->all()];
        }
        else
        {
            $this->setUpCheckOutData($request);
        }

        // Validate the discountCode if it was used
        if($request->has('discountCode') && !$this->validateDiscountCode($request->input('discountCode')))
        {
            return ['validation'=>false,"error"=>["error"=>"Not a valid discount code"]];
        }

        return ['validation'=>true,'error' => []];
    }


    /**
     * Sets up checkOutData array from the request
     *
     * @return void
     */
    private function setUpCheckOutData(Request $request)
    {
        $this->checkoutData['name']=$request->name;
        $this->checkoutData['email']=$request->email;
        $this->checkoutData['country']=$request->country;
        $this->checkoutData['city']=$request->city;
        $this->checkoutData['address']=$request->address;
    }


    /**
     * Creates Validator for checkout form
     *
     * @return Validator
     */
    private function createCheckoutValidator(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email'],
            'country' => ['required', 'min:3'],
            'city' => ['required', 'min:3'],
            'address' => ['required', 'min:3'],
        ]);
    }


    /**
     * Validates discountCode submited by the user
     *
     * @return Validator
     */
    private function validateDiscountCode(string $discountCode)
    {
        if (Auth::check())
        {
            $result=DiscountCode::retrieveUsersDiscountCode($discountCode,Auth::id());
            if($result)
            {
                $this->checkoutData['discountCode']=$result;
                return true;
            }
        }
        return false;
    }



}
