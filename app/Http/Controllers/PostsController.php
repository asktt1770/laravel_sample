<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;

class PostsController extends Controller
{
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->get();

        return view('posts.index', ['posts' => $posts]);
    }

    /* create */
    
    public function create()
    {
    return view('posts.create');
    }

    /* store */

    public function store(Request $request)
    {
    $params = $request->validate([
        'title' => 'required|max:50',
        'body' => 'required|max:2000',
       ]);

        Post::create($params);

        return redirect()->route('top');
    }

    /* show */

    public function show($post_id)
    {
        $post = Post::findOrFail($post_id);

        return view('posts.show', [
        'post' => $post,
        ]);
    }

    /* edit */

    public function edit($post_id)
    {
    $post = Post::findOrFail($post_id);

    return view('posts.edit', [
        'post' => $post,
    ]);
    }

    /* update */

    public function update($post_id, Request $request)
    {
    $params = $request->validate([
        'title' => 'required|max:50',
        'body' => 'required|max:2000',
    ]);

    $post = Post::findOrFail($post_id);
    $post->fill($params)->save();
    
    return redirect()->route('posts.show', [
        'post' => $post,
        ]);
    }

    /* destroy */

    public function destroy($post_id)
    {
    $post = Post::findOrFail($post_id);

    \DB::transaction(function () use ($post) {
        $post->comments()->delete();
        $post->delete();
    });

    return redirect()->route('top');
    }
}