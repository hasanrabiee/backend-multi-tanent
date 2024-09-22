<?php

namespace App\Http\Controllers;

use App\Http\Middleware\AdminMiddleware;
use App\Http\Requests\posts\CreatePostRequest;
use App\Http\Requests\posts\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(middleware: 'auth:sanctum',except:['getPostImage']),
            new Middleware(middleware: AdminMiddleware::class, except: ['index', 'show','getPostImage']),
        ];
    }

    /**
     * Display a listing of the resource.
     * Show all posts.
     */
    public function index()
    {
        // Fetch all posts with their associated user
        $posts = Post::with('user')->get();
        
        return response()->json($posts);
    }

    /**
     * Store a newly created resource in storage.
     * Create a new post.
     */
    public function store(CreatePostRequest $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validated();
        if ($request->hasFile('image')) {
            // Store the image in the 'public/images' directory and get the path
            $imagePath = $request->file('image')->store('images', 'public');
            $validatedData['image_path'] = $imagePath; // Save the image path in the data to be stored
        }
        // Create a new post for the authenticated user
        $post = $request->user()->posts()->create($validatedData);

        return response()->json([
            'message' => 'Post created successfully!',
            'post' => $post
        ], 201); // Status code 201 for created resource
    }

    /**
     * Display the specified resource.
     * Show a specific post.
     */
    public function show(Post $post)
    {
        // Show post with its associated user
        $post->load('user');

        return response()->json($post);
    }

    /**
     * Update the specified resource in storage.
     * Update an existing post.
     */
    public function update(UpdatePostRequest $request, Post $post)
    {   
       
        $validatedData = $request->validated();
        // Ensure the authenticated user is the owner of the post
        
        if ($request->user()->id !== $post->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403); // Forbidden
        }

        if ($request->hasFile('image')) {
            // Store the image in the 'public/images' directory and get the path
            $imagePath = $request->file('image')->store('images', 'public');
            $validatedData['image_path'] = $imagePath; // Save the image path in the data to be stored
        }

        // Update the post
        $post->update($validatedData);

        return response()->json([
            'message' => 'Post updated successfully!',
            'post' => $post
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * Delete a specific post.
     */
    public function destroy(Request $request, Post $post)
    {
        // Ensure the authenticated user is the owner of the post
        if ($request->user()->id !== $post->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403); // Forbidden
        }

        // Delete the post
        $post->delete();

        return response()->json(['message' => 'Post deleted successfully']);
    }

    public function getPostImage($filename){
        $file = Storage::disk('local')->get("public/images/{$filename}");
        if(!$file){
            abort(404);
        }
        $mimeType = Storage::disk('local')->mimeType('public/images/' . $file);
        return response($file, 200)->header('Content-Type', $mimeType);
    }
}
