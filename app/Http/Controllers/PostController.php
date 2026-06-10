<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use App\Services\PostService;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json($this->postService->getFeed($request), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'text' => 'required|string',
        ]);

        try {
            $result = $this->postService->create(
                $request->user(),
                $validated['title'],
                $validated['text']
            );

            return response()->json($result, 201);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json($post->load('user'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'text' => 'sometimes|string',
        ]);

        $post->update($validated);

        return response()->json($post->load('user'), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return response()->json(['message' => 'post successfully deleted'], 200);
    }

    public function myPosts(Request $request)
    {
        return response()->json($this->postService->getUserPosts($request), 200);
    }
}
