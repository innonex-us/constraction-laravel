<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\View\View;

class NewsController extends Controller
{
    public function index(): View
    {
        $posts = Post::query()->where('is_published', true)->latest('published_at')->paginate(9);
        return view('news.index', compact('posts'));
    }

    public function show(string $slug): View
    {
        $post = Post::where('slug', $slug)->where('is_published', true)->firstOrFail();
        $latest = Post::where('is_published', true)->latest('published_at')->where('id','!=',$post->id)->take(5)->get();
        return view('news.show', compact('post','latest'));
    }
}
