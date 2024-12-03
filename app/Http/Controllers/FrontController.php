<?php

namespace App\Http\Controllers;

use App\Mail\ContactEmail;
use App\Models\News;
use App\Models\Page;
use App\Models\Product;
use App\Models\User;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class FrontController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('id', 'DESC')
            ->where('is_featured', 'Yes')
            ->take(8)
            ->where('status', 1)
            ->get();
        $data['featuredProducts'] = $products;
        $latestProducts = Product::orderBy('products.id', 'DESC')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->whereIn('categories.name', ['Android', 'IOS'])
            ->where('products.status', 1)
            ->take(16)
            ->get(['products.*']);

        $data['latestProducts'] = $latestProducts;

        $accessoryProducts = Product::orderBy('products.id', 'DESC')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('categories.name', 'accessory')
            ->where('products.status', 1)
            ->take(8)
            ->get(['products.*']);

        $data['accessoryProducts'] = $accessoryProducts;

        $new_blogs = News::orderBy('id', 'DESC')
            ->take(4)
            ->get();
        $data['new_blogs'] = $new_blogs;

        return view('front.home', $data);
    }
    public function detailNew($id)
    {
        $new = News::find($id);
        return view('front.detail-news', compact('new'));
    }

    public function aboutUs()
    {
        return view('front.about-us');
    }
    public function contactUs()
    {
        return view('front.contact-us');
    }
    public function addToWishlist(Request $request)
    {

        if (Auth::check() == false) {

            session(['url.intended' => url()->previous()]);

            return response()->json([
                'status' => false,
            ]);
        }
        $product = Product::where('id', $request->id)->first();

        if ($product == null) {
            return response()->json([
                'status' => true,
                'message' => '<div class="alert alert-danger"> Product not found. </div>'
            ]);
        }

        Wishlist::updateOrCreate(
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id
            ],
            [
                'user_id' => Auth::user()->id,
                'product_id' => $request->id
            ],
        );
        // $wishlist = new Wishlist();
        // $wishlist->user_id = Auth::user()->id;
        // $wishlist->product_id = $request->id;
        // $wishlist->save();

        return response()->json([
            'status' => true,
            'message' => '<div class="alert alert-success"><strong>"' . $product->title . '"</strong> added in your wish list'
        ]);
    }
    public function page($slug)
    {
        $page = Page::where('slug', $slug)->first();
        // if ($page == null) {
        //     abort(404);
        // }
        return view('front.page', [
            'page' => $page
        ]);
    }
    public function sendContactEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'subject' => 'required',
            'message' => 'required'
        ]);

        if ($validator->passes()) {
            // Send email here
            $mailData = [
                'name' => $request->name,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'mail_subject' => 'You have received a contact email'
            ];

            $admin = User::where('id', 3)->first();
            Mail::to($admin->email)->send(new ContactEmail($mailData));

            Session::flash('success', 'Thank you for contacting us, we will get back to you soon');
            return response()->json([
                'status' => true,
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
}
