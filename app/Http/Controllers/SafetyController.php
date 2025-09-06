<?php

namespace App\Http\Controllers;

use App\Models\SafetyRecord;
use Illuminate\View\View;

class SafetyController extends Controller
{
    public function index(): View
    {
        $records = SafetyRecord::query()->orderByDesc('year')->get();
        $latest = $records->first();
        return view('safety.index', compact('records','latest'));
    }
}
