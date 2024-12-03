<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SizeController extends Controller
{
    public function index(Request $request)
    {
        $sizes = Size::latest('id');
        if ($request->get('keyword')) {
            $sizes = $sizes->where('name', 'like', '%' . $request->keyword . '%');
        }
        $sizes = $sizes->paginate(5);

        return view('admin.sizes.list', compact('sizes'));
    }

    public function create()
    {
        return view('admin.sizes.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:sizes',
            'product_id' => 'required',
        ]);
        if ($validator->passes()) {
            $size = new Size();
            $size->product_id = $request->product_id;
            $size->name = $request->name;
            $size->price = $request->price;

            $size->save();
            Session::flash('success', 'Size created successfully');
            return response()->json([
                'status' => true,
                'message' => 'Sizes create successfully',
            ]);
        }
    }
    public function edit($id, Request $request)
    {
        $size = Size::find($id);
        if (empty($sizes)) {
            return response()->json([
                'status' => false,
                'message' => 'Size Not Found',
            ]);
        }
        $data['size'] = $size;
        return view('admin.sizes.edit', $data);
    }
    public function update($id, Request $request)
    {

        $size = Size::find($id);
        if (empty($sizes)) {
            return response()->json([
                'status' => false,
                'message' => 'Size Not Found',
            ]);
        }
        $size->product_id = $request->product_id;
        $size->name = $request->name;
        $size->price = $request->price;
        $size->save();
        Session::flash('success', 'Size updated successfully');

        return response()->json([
            'status' => false,
            'message' => 'Size updated successfully',
        ]);
    }
    public function delete(Request $request, $id)
    {
        $sizes = Size::find($id);
        if (empty($sizes)) {
            return response()->json([
                'status' => false,
                'message' => 'Size not found'
            ]);
        }
        $sizes->delete();
        Session::flash('success', 'Size deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Size deleted successfully'
        ]);
    }
}