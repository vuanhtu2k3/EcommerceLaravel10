<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::latest();
        if (!empty($request->get('keyword'))) {
            $users =   $users->where('name', 'like', '%' . $request->get('keyword') . '%');
            $users =   $users->orWhere('email', 'like', '%' . $request->get('keyword') . '%');
        }
        $users = $users->paginate(10);

        return view('admin.users.list', [
            'users' => $users
        ]);
    }
    public function create(Request $request)
    {
        return view('admin.users.create');
    }
    // public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required',
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required|min:4',
    //         'phone' => 'required'

    //     ]);
    //     if ($validator->passes()) {
    //         $user = new User();
    //         $user->name = $request->name;
    //         $user->email = $request->email;
    //         $user->password = Hash::make($request->password);
    //         $user->phone = $request->phone;
    //         $user->save();

    //         Session::flash('success', ' User added successfully');

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'User added successfully'
    //         ]);
    //     } else {
    //         return response()->json([
    //             'status' => false,
    //             'errors' => $validator->errors()
    //         ]);
    //     }
    // }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4',
            'phone' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->status = $request->status;
        $user->password = Hash::make($request->password);
        $user->save();
        Session::flash('success', 'User created successfully');

        return response()->json([
            'status' => true,
            'message' => 'User created successfully'
        ]);
    }

    public function edit(Request $request, $id)
    {
        $user = User::find($id);
        if ($user == null) {
            Session::flash('error', 'User not found');
            return redirect()->route('users.index');
        }
        return view('admin.users.edit', [
            'user' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if ($user == null) {
            return response()->json([
                'status' => false,
                'errors' => ['user' => 'User not found']
            ]);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id . ',id',
            'password' => 'sometimes|nullable|min:4',
            'phone' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
        Session::flash('success', 'User updated successfully');
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->status = $request->status;
        if ($request->password != '') {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User updated successfully'
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::find($id);
        if ($user == null) {
            Session::flash('error', 'User not found');
            return response()->json([
                'status' => false,
                'errors' => 'User not found'
            ]);
        }
        $user->delete();
        Session::flash('success', 'User deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'User deleted successfully'
        ]);
    }
}
