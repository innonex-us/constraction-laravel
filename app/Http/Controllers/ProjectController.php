<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        $projects = Project::query()->latest('completed_at')->paginate(12);
        return view('projects.index', compact('projects'));
    }

    public function show(string $slug): View
    {
        $project = Project::where('slug', $slug)->firstOrFail();
        $related = Project::where('id', '!=', $project->id)->latest('completed_at')->take(6)->get();
        return view('projects.show', compact('project', 'related'));
    }
}
