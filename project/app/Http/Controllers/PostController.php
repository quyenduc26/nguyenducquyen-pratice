<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use DB;

/**
 * @OA\Info(
 *      title="API Documentation",
 *      version="1.0.0",
 *      description="API documentation for your application",
 * )

 * @OA\Get(
 *     path="/api/posts",
 *     summary="Get all posts",
 *     @OA\Response(response="200", description="Success"),
 *     security={{"bearerAuth": {}}}
 * )
 *
 * @OA\Put(
 *     path="/api/posts/{id}",
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
 *     path="/api/posts/{id}",
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
 *     path="/api/posts",
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
 *     path="/api/posts/{id}",
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

class PostController extends Controller
{
    public function index()
    {
        $posts = DB::table('posts')->get();
        return $posts;
    }

    public function update(Request $request)
    {
        DB::table('posts')
        ->where('id', $request->id)
        ->update([
            'title' => $request->input('title'),
            'description' => $request->input('description')
        ]);
        $post = Post::find($request->id);

        if (!$post) {
            return response()->json(['message' => 'Không tìm thấy bài viết'], 404);
        }
        return response()->json($post);
    }

    public function show(Request $request) 
    {
        $post = Post::find($request->id);

        if (!$post) {
            return response()->json(['message' => 'Không tìm thấy bài viết'], 404);
        }
        return response()->json($post);
    }

    public function store(Request $request)
    {   
        $post = Post::create([
        'title' => $request['title'],
        'description' => $request['description'],
        ]);

        return response()->json($post, 201);
    }

    public function destroy(Request $request)
    {
        $id = $request->query('id');
        DB::table('posts')->where('id', $request->id)->delete();
        return response()->json(['message' => 'Xóa thành công']);
    }
}