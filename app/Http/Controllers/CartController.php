<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\DiscountCoupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingCharge;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {

        $product = Product::with('product_images')->find($request->id);
        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ]);
        }

        if (Cart::count() > 0) {

            // Products found in cart
            // Check if this product already in ther cart
            // Return as message that products already added in your cart
            // If product not found in the cart, then add product in cart
            $cartContent = Cart::content();
            $productAlreadyExist = false;
            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExist = true;
                }
            }
            if ($productAlreadyExist == false) {
                Cart::add(
                    $product->id,
                    $product->title,
                    1,
                    $product->price,
                    ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']
                );
                $status = true;
                $message = $product->title . ' added in cart';
                Session::flash('success', $message);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => $product->title . ' already in cart'
                ]);
                Session::flash('error', $product->title . ' already in cart');
            }
        } else {
            // Cart empty
            Cart::add($product->id, $product->title, 1, $product->price, ['productImage' => (!empty($product->product_images)) ? $product->product_images->first() : '']);
            $status = true;
            $message = $product->title . ' added in cart successfully';
            Session::flash('success', $message);
        }
        return response()->json([
            'status' => $status,
            'message' =>  $message
        ]);
    }



    public function cart()
    {
        $cartContent = Cart::content();
        //dd($cartContent);
        $data['cartContent'] = $cartContent;
        return view('front.cart', $data);
    }
    public function updateCart(Request $request)
    {
        $rowId = $request->rowId;
        $qty = $request->qty;

        $itemInfo = Cart::get($rowId);
        $product = Product::find($itemInfo->id);
        // Check qty avaiable in stock
        if ($product->track_qty == 'Yes') {
            if ($qty <= $product->qty) {
                Cart::update($rowId, $qty);
                $message = 'Cart updated successfully';
                $status = true;
                Session::flash('success', $message);
            } else {
                $message = 'Request qty(' . $qty . ') not avaiable ';
                $status = false;
                Session::flash('error', $message);
            }
        } else {
            Cart::update($rowId, $qty);
            $message = 'Cart updated successfully';
            $status = true;
            Session::flash('success', $message);
        }



        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function deleteItem(Request $request)
    {

        $itemInfo = Cart::get($request->rowId);


        if ($itemInfo == null) {
            $errorMessage = 'Item not found in cart';
            Session::flash('error', $errorMessage);
            return response()->json([
                'status' => false,
                'message' =>  $errorMessage

            ]);
        }
        Cart::remove($request->rowId);

        $message = 'Item removed from cart successfully';
        Session::flash('success', $message);
        return response()->json([
            'status' => true,
            'message' =>  $message

        ]);
    }

    public function checkout()
    {
        $discount = 0;
        //if cart is empty
        if (Cart::count() == 0) {
            return redirect()->route('front.cart');
        }
        //if user is not logged in in the redirect to login page


        if (Auth::check() == false) {

            if (!session()->has('url.intended')) {
                session(['url.intended' => url()->current()]);
            }
            return redirect()->route('account.login');
        }

        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();
        session()->forget('url.intended');
        $countries = Country::orderBy('name', 'ASC')->get();


        $subTotal = Cart::subtotal(3, '.', '');
        //Apply Discount Here
        if (session()->has('code')) {
            $code = session()->get('code');
            if ($code->type == 'percent') {
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }
        }

        // Calculate shipping here
        if ($customerAddress != '') {
            $userCountry = $customerAddress->country_id;
            $shippingInfo = ShippingCharge::where('country_id', $userCountry)->first();

            if ($shippingInfo != null) {
                $totalQty = 0;

                foreach (Cart::content() as $item) {
                    $totalQty += $item->qty;
                }

                $totalShippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $totalShippingCharge;
            } else {
                $totalShippingCharge = 0;
                $grandTotal = Cart::subtotal(3, '.', '');
            }
        } else {
            $grandTotal = ($subTotal - $discount);
            $totalShippingCharge = 0;
        }

        return view('front.checkout', [
            'countries' => $countries,
            'customerAddress' => $customerAddress,
            'totalShippingCharge' => $totalShippingCharge,
            'discount' => $discount,
            'grandTotal' => $grandTotal
        ]);
    }
    public function processCheckout(Request $request)
    {
        // Apply  validation
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:5',
            'last_name' => 'required',
            'email' => 'required|email',
            'address' => 'required|min:5',
            'city' => 'required',
            'country' => 'required',
            'mobile' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => "Please fix the error",
                'errors' => $validator->errors()
            ]);
        }

        // step 2 Save user address

        //$customerAddress =  CustomerAddress::find();

        $user = Auth::user();
        CustomerAddress::updateOrCreate(
            [
                'user_id' => $user->id,
            ],
            [
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'mobile' => $request->mobile,
                'country_id' => $request->country,
                'address' => $request->address,
                'city' => $request->city
            ]
        );
        // step 3: store data in orders table
        if ($request->payment_method == 'cod') {

            $discountCodeId = NULL;
            $promoCode = '';
            // Calculate shipping
            $shipping = 0;
            $discount = 0;
            $subTotal = Cart::subtotal(3, '.', '');
            $grandTotal = $subTotal +  $shipping;

            // Apply discount here
            if (session()->has('code')) {
                $code = session()->get('code');
                if ($code->type == 'percent') {
                    $discount = ($code->discount_amount / 100) * $subTotal;
                } else {
                    $discount = $code->discount_amount;
                }
                $discountCodeId = $code->id;
                $promoCode = $code->code;
            }

            $shippingInfo =  ShippingCharge::where('country_id', $request->country)->first();
            $totalQty = 0;

            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null) {
                $shipping = $totalQty * $shippingInfo->amount;
                $grandTotal =  ($subTotal - $discount) + $shipping;
            } else {

                $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();

                $shipping = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $shipping;
            }


            $order = new Order();
            $order->subtotal = $subTotal;
            $order->shipping = $shipping;
            $order->grand_total = $grandTotal;
            $order->discount = $discount;
            $order->coupon_code_id = $discountCodeId;
            $order->coupon_code = $promoCode;
            $order->payment_status = 'not paid';
            $order->status = 'pending';
            $order->user_id = $user->id;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->country_id = $request->country;
            $order->address = $request->address;
            $order->city = $request->city;
            $order->notes = $request->order_notes;
            $order->save();

            // step 4 store order items in items table

            foreach (Cart::content() as $item) {
                $orderItem = new OrderItem();
                $orderItem->product_id = $item->id;
                $orderItem->order_id = $order->id;
                $orderItem->name = $item->name;
                $orderItem->qty = $item->qty;
                $orderItem->price = $item->price;
                $orderItem->total = $item->price * $item->qty;
                $orderItem->save();

                // Update product stock
                $productData = Product::find($item->id);
                if ($productData->track_qty == 'Yes') {
                    $currentQty = $productData->qty;
                    $updatedQty = $currentQty - $item->qty;
                    $productData->qty = $updatedQty;
                    $productData->save();
                }
            }
            // Send Order Email
            //orderEmail($order->id, 'customer');

            Session::flash('success', 'You have placed your order succesfully');

            Cart::destroy();
            session()->forget('code');
            return response()->json([
                'status' => true,
                'message' => "Order saved successfully",
                'orderId' => $order->id,

            ]);
        }
    }
    public function thankyou($id)
    {
        return view('front.thanks', [
            'id' => $id
        ]);
    }

    public function getOrderSummery(Request $request)
    {
        $subTotal = Cart::subtotal(3, '.', '');
        $discount = 0;
        $discountString = '';
        //Apply Discount Here
        if (session()->has('code')) {
            $code = session()->get('code');
            if ($code->type == 'percent') {
                $discount = ($code->discount_amount / 100) * $subTotal;
            } else {
                $discount = $code->discount_amount;
            }
            $discountString = '<div class="mt-4" id="discount-response">
        <strong>' . session()->get('code')->code . '</strong>
        <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
        </div>';
        }

        if ($request->country_id > 0) {


            $shippingInfo =  ShippingCharge::where('country_id', $request->country_id)->first();

            $totalQty = 0;

            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }
            if ($shippingInfo != null) {
                $shippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal, 3),
                    'discount' => number_format($discount, 3),
                    'discountString' =>  $discountString,
                    'shippingCharge' => number_format($shippingCharge, 3)
                ]);
            } else {

                $shippingInfo = ShippingCharge::where('country_id', 'rest_of_world')->first();

                $shippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal = ($subTotal - $discount) + $shippingCharge;

                return response()->json([
                    'status' => true,
                    'grandTotal' => number_format($grandTotal, 3),
                    'discount' => number_format($discount, 3),
                    'discountString' =>  $discountString,
                    'shippingCharge' => number_format($shippingCharge, 3)
                ]);
            }
        } else {

            $grandTotal  = 0;
            return response()->json([
                'status' => true,
                'subTotal' => number_format(($subTotal - $discount), 3),
                'discount' => $discount,
                'discountString' =>  $discountString,
                'shippingCharge' => number_format(0, 3)

            ]);
        }
    }
    public function applyDiscount(Request $request)
    {

        $code = DiscountCoupon::where('code', $request->code)->first();
        if ($code == null) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid Coupon Code'

            ]);
        }
        // Check if coupon start date is valid or not
        $now = Carbon::now();
        // if ($code->starts_at != "") {
        //     $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->starts_at);
        //     if ($now->lt($startDate)) {
        //         return response()->json([
        //             'status' => false,
        //             'message' => 'Invalid Coupon Code1'

        //         ]);
        //     }
        // }
        if ($code->expires_at != "") {
            $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $code->expires_at);
            if ($now->gt($endDate)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid Coupon Code2'

                ]);
            }
        }

        //Max Uses  check
        if ($code->max_uses > 0) {

            $couponUsed = Order::where('coupon_code_id', $code->id)->count();

            if ($couponUsed >= $code->max_uses) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid discount coupon'
                ]);
            }
        }


        //Max Uses User check
        if ($code->max_uses_user > 0) {
            $couponUsedByUser = Order::where(['coupon_code_id' => $code->id, 'user_id' => Auth::user()->id])->count();
            if ($couponUsedByUser >= $code->max_uses_user) {
                return response()->json([
                    'status' => false,
                    'message' => 'You already used this coupon code'
                ]);
            }
        }

        // Min amount  condition check
        $subTotal = Cart::subtotal(2, '.', '');
        if ($code->min_amount) {
            if ($subTotal < $code->min_amount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Your min amount must be $' . $code->min_amount . '.',
                ]);
            }
        }


        session()->put('code', $code);
        return $this->getOrderSummery($request);
    }
    public function removeCoupon(Request $request)
    {
        session()->forget('code');
        return $this->getOrderSummery($request);
    }
}
