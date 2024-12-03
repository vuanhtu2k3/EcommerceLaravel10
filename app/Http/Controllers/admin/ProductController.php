<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductRating;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $products = Product::latest('id')
            ->with('product_images')
            ->with('colors')
            ->with('sizes');

        if ($request->get('keyword') != "") {
            $products = $products->where('title', 'like', '%' . $request->keyword . '%');
        }

        $products = $products->paginate();
        $data['products'] = $products;
        return view("admin.products.list", $data);
    }
    public function create()
    {
        $data = [];
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['brands'] = $brands;
        return view('admin.products.create', $data);
    }

    public function store(Request $request)
    {

        $rules =  [
            'title' => 'required',
            'slug' => 'required|unique:products',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];
        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }
        $validator =  Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $product = new Product();
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->qty = $request->qty;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->shipping_returns = $request->shipping_returns;
            $product->short_description = $request->short_description;
            $product->related_products = (!empty($request->related_products)) ? implode(',', $request->related_products) : '';

            $product->save();

            // Save Gallery Pics
            if (is_array($request->image_array)) {
                foreach ($request->image_array as $temp_image_id) {
                    $tempImageInfo = TempImage::find($temp_image_id);
                    if ($tempImageInfo) {
                        $extArray = explode('.', $tempImageInfo->name);
                        $ext = last($extArray);

                        $productImage = new ProductImage();
                        $productImage->product_id = $product->id;
                        $productImage->image = 'NULL';
                        $productImage->save();

                        $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
                        $productImage->image = $imageName;
                        $productImage->save();

                        // Large Image
                        $sourcePath = public_path() . '/temp/' . $tempImageInfo->name;
                        $destLargePath = public_path() . '/uploads/product/large/' . $imageName;
                        $image = Image::make($sourcePath);
                        $image->resize(1400, null, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $image->save($destLargePath);

                        // Small Image
                        $destSmallPath = public_path() . '/uploads/product/small/' . $imageName;
                        $image = Image::make($sourcePath);
                        $image->resize(300, 300);
                        $image->save($destSmallPath);
                    }
                }
            }




            Session::flash('success', 'Products added successfully!');

            return response()->json([
                'status' => true,
                'message' => 'Product added successfully'
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
        $product = Product::find($id);

        if (empty($product)) {
            Session::flash('error', 'Products not found');

            return redirect()->route('products.index')
                ->with('error', 'Product not found');
        }

        // Fetch Product Images
        $productImages = ProductImage::where('product_id', $product->id)->get();

        $subCategories = SubCategory::where('category_id', $product->category_id)->get();

        $relatedProducts = [];
        //Fetch Related products
        if ($product->related_products != '') {
            $productArray = explode(',', $product->related_products);

            $relatedProducts = Product::whereIn('id', $productArray)->with('product_images')->get();
        }

        $data = [];
        $data['product'] = $product;
        $data['subCategories'] = $subCategories;
        $categories = Category::orderBy('name', 'ASC')->get();
        $brands = Brand::orderBy('name', 'ASC')->get();
        $data['categories'] = $categories;
        $data['productImages'] = $productImages;
        $data['relatedProducts'] = $relatedProducts;
        $data['brands'] = $brands;
        return view('admin.products.edit', $data);
    }

    public function update($id, Request $request)
    {

        $product = Product::find($id);

        $rules =  [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,' . $product->id . ',id',

            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,' . $product->id . ',id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
        ];
        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rules['qty'] = 'required|numeric';
        }
        $validator =  Validator::make($request->all(), $rules);
        if ($validator->passes()) {
            $product->title = $request->title;
            $product->slug = $request->slug;
            $product->qty = $request->qty;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->compare_price = $request->compare_price;
            $product->sku = $request->sku;
            $product->barcode = $request->barcode;
            $product->track_qty = $request->track_qty;
            $product->status = $request->status;
            $product->category_id = $request->category;
            $product->sub_category_id = $request->sub_category;
            $product->brand_id = $request->brand;
            $product->is_featured = $request->is_featured;
            $product->shipping_returns = $request->shipping_returns;
            $product->short_description = $request->short_description;
            $product->related_products = (!empty($request->related_products)) ? implode(',', $request->related_products) : '';


            $product->save();

            Session::flash('success', 'Products update successfully!');

            return response()->json([
                'status' => true,
                'message' => 'Product updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }
    public function destroy($id, Request $request)
    {
        $product = Product::find($id);

        if (empty($product)) {
            Session::flash('error', 'Products not found!');
            return response()->json([
                'status' => false,
                'notFound' => true
            ]);
        }
        $productImages = ProductImage::where('product_id', $id)->get();

        if (!empty($productImages)) {
            foreach ($productImages as $productImage) {
                File::delete(public_path('uploads/product/large/' . $productImage->image));
                File::delete(public_path('uploads/product/small/' . $productImage->image));
            }
            ProductImage::where('product_id', $id)->delete();
        }
        $product->delete();

        Session::flash('success', 'Products deleted successfully!');



        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully'
        ]);
    }

    public function getProducts(Request $request)
    {
        $tempProduct = [];
        if ($request->term != "") {
            $products = Product::where('title', 'like', '%' . $request->term . '%')->get();

            if ($products != null) {
                foreach ($products as $product) {
                    $tempProduct[] = array('id' => $product->id, 'text' => $product->title);
                }
            }
        }
        return response()->json([
            'tags' => $tempProduct,
            'status' => true
        ]);
    }
    public function productRatings(Request $request)
    {
        $ratings = ProductRating::select('product_ratings.*', 'products.title as productTitle')->orderBy('product_ratings.created_at', 'DESC');
        $ratings = $ratings->leftJoin('products', 'products.id', 'product_ratings.product_id');

        if ($request->get('keyword') != "") {
            $ratings = $ratings->orWhere('products.title', 'like', '%' . $request->keyword . '%');
            $ratings = $ratings->orWhere('product_ratings.username', 'like', '%' . $request->keyword . '%');
        }

        $ratings = $ratings->paginate(10);
        return view('admin.products.ratings', [
            'ratings' => $ratings
        ]);
    }
    public function changeRatingStatus(Request $request)
    {

        $productRating = ProductRating::find($request->id);
        $productRating->status = $request->status;
        $productRating->save();

        Session::flash('success', 'Status changed successfully');
        return response()->json([
            'status' => true,
            'message' => 'Rating status changed successfully'
        ]);
    }
}
