<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Project;
use App\Models\Testimonial;
use App\Models\SiteSetting;
use App\Models\Post;
use App\Models\Client;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $settings = SiteSetting::first();
        
        // Get data based on settings
        $services = $settings?->show_services_section 
            ? Service::query()->where('is_active', true)->orderBy('order')->take($settings->services_limit ?? 6)->get()
            : collect();
            
        $projects = $settings?->show_projects_section
            ? Project::query()->orderByDesc('is_featured')->latest('completed_at')->take($settings->projects_limit ?? 6)->get()
            : collect();
            
        $testimonials = $settings?->show_testimonials_section
            ? Testimonial::query()->orderBy('order')->take($settings->testimonials_limit ?? 6)->get()
            : collect();
            
        $posts = $settings?->show_news_section
            ? Post::query()->where('is_published', true)->latest('published_at')->take($settings->news_limit ?? 3)->get()
            : collect();
            
        $clients = $settings?->show_clients_section
            ? Client::query()->where('is_active', true)->orderBy('order')->get()
            : collect();

        return view('home', compact('settings', 'services', 'projects', 'testimonials', 'posts', 'clients'));
    }
}

