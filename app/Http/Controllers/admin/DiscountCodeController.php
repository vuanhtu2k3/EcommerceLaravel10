<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class DiscountCodeController extends Controller
{
    public function index(Request $request)
    {
        $discountCoupons = DiscountCoupon::latest();

        if (!empty($request->get('keyword'))) {
            $discountCoupons = $discountCoupons->where('name', 'like', '%' . $request->get('keyword') . '%');
            $discountCoupons = $discountCoupons->orWhere('code', 'like', '%' . $request->get('keyword') . '%');
        }
        $discountCoupons = $discountCoupons->paginate(10);

        return view('admin.coupon.list', compact('discountCoupons'));
    }
    public function create()
    {
        return view('admin.coupon.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'starts_at' => 'required',
            'status' => 'required',

        ]);
        if ($validator->passes()) {
            // Starting date must be greate than current date

            if (!empty($request->starts_at)) {
                $now = Carbon::now();
                $startAt =  Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                if ($startAt->lte($now) == true) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['starts_at' => ' Start date can not be less than current time']
                    ]);
                }
            }
            // Expriry date must be greator than start date
            if (!empty($request->starts_at) && !empty($request->expires_at)) {
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
                $startAt =  Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                if ($expiresAt->gt($startAt) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => ' Expires date must be greator than start date']
                    ]);
                }
            }


            $discountCode = new DiscountCoupon();
            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->status = $request->status;
            $discountCode->starts_at = $request->starts_at;
            $discountCode->expires_at = $request->expires_at;
            $discountCode->save();


            Session::flash('success', 'Discount Coupon created successfully');
            return response()->json([
                'status' => true,
                'message' => 'Discount Coupon created successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id, Request $request)
    {


        $coupon = DiscountCoupon::find($id);
        if ($coupon == null) {
            Session::flash('error', ' Record not found');
            return redirect()->route('coupons.index');
        }
        $data['coupon'] = $coupon;
        return view('admin.coupon.edit', $data);
    }

    public function update($id, Request $request)
    {
        $discountCode = DiscountCoupon::find($id);

        if ($discountCode == null) {
            Session::flash('error', 'Record not found');
            return response()->json([
                'status' => true
            ]);
        }
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'type' => 'required',
            'discount_amount' => 'required|numeric',
            'starts_at' => 'required',
            'status' => 'required',

        ]);
        if ($validator->passes()) {
            // // Expriry date must be greator than start date
            if (!empty($request->starts_at) && !empty($request->expires_at)) {
                $expiresAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->expires_at);
                $startAt =  Carbon::createFromFormat('Y-m-d H:i:s', $request->starts_at);
                if ($expiresAt->gt($startAt) == false) {
                    return response()->json([
                        'status' => false,
                        'errors' => ['expires_at' => ' Expires date must be greator than start date']
                    ]);
                }
            }

            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->type = $request->type;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->status = $request->status;
            $discountCode->starts_at = $request->starts_at;
            $discountCode->expires_at = $request->expires_at;
            $discountCode->save();


            Session::flash('success', 'Discount Coupon updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'Discount Coupon updated successfully',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function destroy(Request $request, $id)
    {
        $discountCode = DiscountCoupon::find($id);
        if ($discountCode == null) {
            Session::flash('error', 'Record not found');
            return response()->json([
                'status' => true
            ]);
        }
        $discountCode->delete();
        Session::flash('success', 'Discount Coupon deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Discount Coupon deleted successfully',
        ]);
    }
}
