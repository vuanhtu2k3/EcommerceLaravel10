<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ColorController extends Controller
{
    public function index(Request $request)
    {
        $colors = Color::latest('id');

        if ($request->get('keyword')) {
            $colors = $colors->where('name', 'like', '%' . $request->keyword . '%');
        }
        $colors = $colors->paginate(5);
        return view('admin.colors.list', compact('colors'));
    }
    public function create()
    {
        return view('admin.colors.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'product_id' => 'required',
        ]);
        if ($validator->passes()) {
            $color = new Color();
            $color->product_id = $request->product_id;
            $color->name = $request->name;
            $color->save();

            Session::flash('success', 'Color created successfullly');
            return response()->json([
                'status' => true,
                'message' => 'Color created successfullly',
            ]);
        }
    }
    public function edit($id, Request $request)
    {
        $color = Color::find($id);
        if (empty($color)) {
            return response()->json([
                'status' => false,
                'message' => 'Color not found',
            ]);
        }
        $data['color'] = $color;
        return view('admin.colors.edit', $data);
    }

    public function update($id, Request $request)
    {
        $color = Color::find($id);
        if (empty($color)) {
            return response()->json([
                'status' => false,
                'message' => 'Color not found',
            ]);
        }
        $validator = Validator::make($request->all(), [
            'product_id' => 'required',
            'name' => 'required'
        ]);

        if ($validator->passes()) {
            $color->product_id = $request->product_id;
            $color->name = $request->name;
            $color->save();
            Session::flash('success', 'Color updated successfullly');
            return response()->json([
                'status' => true,
                'message' => 'Color updated successfullly',
            ]);
        }
    }


    public function delete($id, Request $request)
    {
        $colors = Color::find($id);

        if (empty($colors)) {
            return response()->json([
                'status' => false,
                'message' => 'Color not found',
            ]);
        }
        $colors->delete();
        Session::flash('success', 'Color deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Color deleted successfully'
        ]);
    }
}
