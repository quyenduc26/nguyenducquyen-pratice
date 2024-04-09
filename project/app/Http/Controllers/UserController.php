<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',    
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        return response()->json($user);
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'string',
            'email' => 'email|unique:users,email,' . $id,
            'password' => 'string|min:6',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name ?? $user->name;
        $user->email = $request->email ?? $user->email;
        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
}
