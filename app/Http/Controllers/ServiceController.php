<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Project;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::query()->where('is_active', true)->orderBy('order')->paginate(12);
        return view('services.index', compact('services'));
    }

    public function show(string $slug): View
    {
        $service = Service::where('slug', $slug)->firstOrFail();
        $related = Service::where('is_active', true)->where('id', '!=', $service->id)->orderBy('order')->take(6)->get();
        $keyword = preg_replace('/[^A-Za-z0-9 ]/', ' ', $service->name);
        $projects = Project::query()
            ->when($keyword, fn($q) => $q->where('category', 'like', '%' . $keyword . '%'))
            ->latest('completed_at')
            ->take(3)
            ->get();
        return view('services.show', compact('service', 'related', 'projects'));
    }
}
