<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = auth()->user()->posts()->orderBy("created_at","desc")->paginate(5);

        return PostResource::collection($posts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);
        $data['slug'] = Str::slug($data['title']);

        auth()->user()->posts()->create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Post created successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();
        $data = $request->validate([
            'title'=> ['required', 'string', 'string'],
            'body'=> ['required', 'string'],
        ]);
         $data['slug'] = Str::slug($data['title']);

        $post->update($data);
        return new PostResource($post);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($slug)
    {
       $post = Post::where('slug', $slug)->firstOrFail();
       $post->delete();
       return response(null, 204);
    }
}
