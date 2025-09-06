<?php

namespace App\Http\Controllers;

use App\Models\Service;
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
        return view('services.show', compact('service', 'related'));
    }
}
