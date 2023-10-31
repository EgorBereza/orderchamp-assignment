<?php

namespace App\Services;
use App\Contracts\OrderServiceInterface;
use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\User;
use App\Notifications\DiscountCodeNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService implements OrderServiceInterface
{
    protected array $cart = [];

    /**
     * Create a new order.
     *
     * @param array $cart
     * @param array $checkoutData
     * @return Order|array
     */
    public function createOrder(array $cart, array $checkoutData)
    {
        if(empty($cart))
        {
            return ["error" => "Cart is empty"];
        }
        $orderData = $this->prepareOrderData($cart, $checkoutData);
        $order=$this->saveOrder($orderData, $cart);
        return $order ? $order : ["error" => "Order creation error"];
    }

    /**
     * Prepare order data.
     *
     * @param array $cart
     * @param array $checkoutData
     * @return array
     */
    private function prepareOrderData(array $cart, array $checkoutData)
    {
        $orderData = [];
        $discountValue = 0;


        if (isset($checkoutData['discountCode'])) 
        {
            $orderData['discount_code_id'] = $checkoutData['discountCode']->id;
            $discountValue = $checkoutData['discountCode']->discount_value;
        }

        $orderData['total_value'] = $this->calculateOrderTotalValue($cart, $discountValue);

        if (Auth::check())
        {
            $orderData['user_id'] = auth()->id();
        }

        return $orderData;
    }

    /**
     * Save the order to the database.
     *
     * @param array $orderData
     * @param array $cart
     * @return Order|null
     */
    private function saveOrder(array $orderData, array $cart)
    {
        DB::beginTransaction();
        try
        {
            $order = Order::create($orderData);
            $this->createOrderProducts($order, $cart);

            if ($order->discount_code_id) 
            {
                $this->deactivateDiscountCode($order->discount_code_id);
            } 
            elseif ($order->user_id) 
            {
                $discountCode=$this->generateDiscountCode($order->user_id);
                $this->sendDiscountCode($discountCode,Auth::user());
            }

            DB::commit();
            return $order;
        }
        catch (\Exception $e)
        {
            dd($e->getMessage());
            DB::rollback();
            return null;
        }
    }

    /**
     * Create order products from the cart.
     *
     * @param Order $order
     * @param array $cart
     */
    private function createOrderProducts(Order $order, array $cart)
    {
        foreach ($cart as $item) 
        {
            $orderProductData = [
                'order_id' => $order->id,
                'product_id' => $item['product']->id,
                'quantity' => $item['quantity'],
            ];
            OrderProduct::create($orderProductData);
        }
    }

    /**
     * Deactivate the discount code.
     *
     * @param string $discountCodeId
     */
    private function deactivateDiscountCode(string $discountCodeId)
    {
        DiscountCode::deactivateDiscountCode($discountCodeId);
    }

    /**
     * Generates a discount code for the user.
     *
     * @param int $userId
     * @return DiscountCode
     */
    private function generateDiscountCode(int $userId)
    {
        return DiscountCode::createDiscountCode($userId);
    }


    /**
     * Add an email notification to a queue that will be sent to the user after 15 minutes.
     *
     * @param DiscountCode $discountCode
     * @param User $user
     */
    private function sendDiscountCode(DiscountCode $discountCode,User $user)
    {
        $user->notify((new DiscountCodeNotification($user,$discountCode))->delay(now()->addMinutes(15)));
    }
      
    /**
     * Calculate the total value of the order.
     *
     * @return float
     */
    private function calculateOrderTotalValue(array $cart,float $discount=0)
    {
        $total = 0;

        foreach ($cart as $item)
        {
            $product = $item['product'];
            $subtotal = $product->price * $item['quantity'];
            $total += $subtotal;
        }

        // Apply discount if available
        $total -= $discount;

        // Ensure the total is not negative
        return max(0, $total);
    }
}
