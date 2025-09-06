<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Project;
use App\Models\Testimonial;
use App\Models\SiteSetting;
use App\Models\Post;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $settings = SiteSetting::first();
        $services = Service::query()->where('is_active', true)->orderBy('order')->take(6)->get();
        $projects = Project::query()->orderByDesc('is_featured')->latest('completed_at')->take(6)->get();
        $testimonials = Testimonial::query()->orderBy('order')->take(6)->get();
        $posts = Post::query()->where('is_published', true)->latest('published_at')->take(3)->get();

        return view('home', compact('settings', 'services', 'projects', 'testimonials', 'posts'));
    }
}

