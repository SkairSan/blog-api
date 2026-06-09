<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class PostService
{
    public function create(User $user, string $title, string $text): Post
    {
        $post = $user->posts()->create([
            'title' => $title,
            'text' => $text,
        ]);

        return $post->load('user');
    }


    public function getFeed(Request $request)
    {
        $query = Post::with('user');
    
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if (!in_array($sortBy, ['title', 'created_at'])) {
            $sortBy = 'created_at';
        }
        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        $query->orderBy($sortBy, $sortDir);
    
        $limit = $request->get('limit', 10);
    
        return $query->paginate($limit);
    }


    public function getUserPosts(Request $request)
    {
        $query = $request->user()->posts();
    
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc');

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if (!in_array($sortBy, ['title', 'created_at'])) {
            $sortBy = 'created_at';
        }
        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        $query->orderBy($sortBy, $sortDir);
    
        $limit = $request->get('limit', 10);
    
        return $query->paginate($limit);
    }
}
