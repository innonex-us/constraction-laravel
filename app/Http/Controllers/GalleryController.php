<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(Request $request): View
    {
        $q = $request->string('q')->toString();
        $category = $request->string('category')->toString();

        $query = GalleryItem::query()->where('is_published', true);
        if ($q) {
            $query->where(function ($s) use ($q) {
                $s->where('title', 'like', "%$q%")
                  ->orWhere('caption', 'like', "%$q%");
            });
        }
        if ($category) {
            $query->where('category', $category);
        }

        $items = $query->orderBy('order')->latest()->paginate(24)->appends($request->query());
        $categories = GalleryItem::query()->select('category')->whereNotNull('category')->distinct()->pluck('category')->filter()->values();

        return view('gallery.index', compact('items', 'categories', 'category', 'q'));
    }
}
