<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $pages = Page::latest();

        if ($request->keyword != '') {
            $pages = $pages->where('name', 'like', '%' . $request->keyword . '%');
        }
        $pages = $pages->paginate(10);
        return view('admin.pages.list', [
            'pages' => $pages
        ]);
    }
    public function create()
    {
        return view("admin.pages.create");
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required'

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $page = new Page();
        $page->name = $request->name;
        $page->slug = $request->slug;
        $page->content = $request->content;
        $page->save();

        Session::flash('success', 'Page created successfully');
        return response()->json([
            'status' => true,
            'message' => 'Page created successfully'
        ]);
    }
    public function edit(Request $request, $id)
    {
        $page = Page::find($id);
        if ($page == null) {

            Session::flash('error', ' Page not found');
            return redirect()->route('pages.index');
        }
        return view('admin.pages.edit', [
            'page' => $page
        ]);
    }
    public function update(Request $request, $id)
    {
        $page = Page::find($id);
        if ($page == null) {
            Session::flash('error', 'Page not found');
            return redirect()->route('pages.index');
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'slug' => 'required'

        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        if ($validator->passes()) {
            $page->name = $request->name;
            $page->slug = $request->slug;
            $page->content = $request->content;
            $page->save();

            Session::flash('success', 'Page updated successfully');
            return response()->json([
                'status' => true,
                'message' => 'Page updated successfully'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => "Page error"
            ]);
        }
    }
    public function destroy(Request $request, $id)
    {
        $page = Page::find($id);
        if ($page == null) {
            return response()->json([
                'status' => false,
                'message' => 'Page not found'
            ]);
        }
        $page->delete();
        Session::flash('success', 'Page deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Page deleted successfully'
        ]);
    }
}
