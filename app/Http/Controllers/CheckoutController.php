<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\CustomerAddress;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Gloudemans\Shoppingcart\Facades\Cart;

class CheckoutController extends Controller
{
    public function show()
    {
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
        return view('front.checkoutVnpay', [
            'customerAddress' => $customerAddress,
            'countries' => $countries,
        ]);
    }

    public function checkoutVnpay(Request $request)
    {
        $user = Auth::user();

        // Update or create customer address
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
                'address' => $request->address,
                'city' => $request->city,
            ]
        );

        if ($request->payment_method == 'vnpay') {
            $order = new Order();
            $order->payment_status = 'not paid';
            $order->status = 'pending';
            $order->user_id = $user->id;
            $order->first_name = $request->first_name;
            $order->last_name = $request->last_name;
            $order->email = $request->email;
            $order->mobile = $request->mobile;
            $order->address = $request->address;
            $order->city = $request->city;
            $order->notes = $request->order_notes;
            $order->shipping = $request->shipping ?? 10000;

            // Calculate subtotal
            $subtotal = 0;
            foreach (Cart::content() as $item) {
                $subtotal += $item->price * $item->qty;
            }
            $order->subtotal = $subtotal; // Assign subtotal to order
            $order->grand_total = $subtotal + $order->shipping;
            $order->country_id = $request->country ?? 1;
            $order->save();
        }

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
        Cart::destroy();

        // VNPAY payment setup
        $code_cart = rand(00, 9999);
        $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
        $vnp_Returnurl = "http://localhost:8000/thanksVnpay";
        $vnp_TmnCode = "D5S2SJJ4";
        $vnp_HashSecret = "6YLXVBMF38ESQ5QX0OBDX57KCIZPLCK4";

        $vnp_TxnRef = $code_cart;
        $vnp_OrderInfo = 'Thanh toan don hang';
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = (float) $subtotal * 100000;
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'NCB';
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];

        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef
        );

        if (isset($vnp_BankCode)) {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }

        ksort($inputData);
        $query = "";
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            $hashdata .= ($hashdata ? '&' : '') . urlencode($key) . "=" . urlencode($value);
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if ($vnp_HashSecret) {
            $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }

        $returnData = [
            'code' => '00',
            'message' => 'success',
            'data' => $vnp_Url
        ];

        return response()->json($returnData);
    }


    public function thankyouVnpay(Request $request)
    {

        return view('front.thanksVnpay');
    }
}