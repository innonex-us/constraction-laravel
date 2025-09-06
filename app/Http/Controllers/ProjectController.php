<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->toString();
        $category = $request->string('category')->toString();
        $status = $request->string('status')->toString();

        $query = Project::query();
        if ($q) {
            $query->where(function ($s) use ($q) {
                $s->where('title', 'like', "%$q%")
                  ->orWhere('excerpt', 'like', "%$q%")
                  ->orWhere('location', 'like', "%$q%")
                  ->orWhere('client', 'like', "%$q%");
            });
        }
        if ($category) $query->where('category', $category);
        if ($status) $query->where('status', $status);

        $projects = $query->latest('completed_at')->paginate(12)->appends($request->query());

        $categories = Project::query()->select('category')->distinct()->pluck('category')->filter()->values();
        $statuses = Project::query()->select('status')->distinct()->pluck('status')->filter()->values();

        return view('projects.index', compact('projects','categories','statuses','q','category','status'));
    }

    public function show(string $slug): View
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        $related = Project::where('id', '!=', $project->id)->latest('completed_at')->take(6)->get();
        return view('projects.show', compact('project', 'related'));
    }

    public function map(): View
    {
        $projects = Project::query()
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->select('title','slug','lat','lng','location','featured_image')
            ->get();
        return view('projects.map', compact('projects'));
    }
}
