<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
/**

 * @OA\Get(
 *     path="/users",
 *     summary="Get all users",
 *     @OA\Response(response="200", description="Success"),
 *     security={{"bearerAuth": {}}}
 * )
 *
 * @OA\Put(
 *     path="/users/{id}",
 *     summary="Update a post",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the post",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="description", type="string")
 *         )
 *     ),
 *     @OA\Response(response="200", description="Success"),
 *     @OA\Response(response="404", description="Post not found"),
 *     security={{"bearerAuth": {}}}
 * )
 *
 * @OA\Get(
 *     path="/users/{id}",
 *     summary="Get a post by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the post",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(response="200", description="Success"),
 *     @OA\Response(response="404", description="Post not found"),
 *     security={{"bearerAuth": {}}}
 * )
 *
 * @OA\Post(
 *     path="/users",
 *     summary="Create a new post",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             @OA\Property(property="title", type="string"),
 *             @OA\Property(property="description", type="string")
 *         )
 *     ),
 *     @OA\Response(response="201", description="Post created"),
 *     security={{"bearerAuth": {}}}
 * )
 *
 * @OA\Delete(
 *     path="/users/{id}",
 *     summary="Delete a post by ID",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the post",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(response="200", description="Post deleted"),
 *     @OA\Response(response="404", description="Post not found"),
 *     security={{"bearerAuth": {}}}
 * )
 */
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
