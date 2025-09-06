<?php

namespace App\Http\Controllers;

use App\Models\GalleryItem;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        $items = GalleryItem::query()->where('is_published', true)->orderBy('order')->latest()->paginate(18);
        return view('gallery.index', compact('items'));
    }
}
