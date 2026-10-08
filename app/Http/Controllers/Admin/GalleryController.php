<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        return view('admin.gallery', ['items' => GalleryItem::orderBy('sort')->get()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['images' => ['required', 'array', 'max:12'], 'images.*' => ['image', 'max:6144'], 'caption' => ['nullable', 'string', 'max:160'], 'instagram_url' => ['nullable', 'url', 'max:255']]);
        $sort = (int) GalleryItem::max('sort') + 1;
        foreach ($request->file('images') as $file) {
            GalleryItem::create(['image_path' => $file->store('gallery', 'public'), 'caption' => $data['caption'] ?? null, 'instagram_url' => $data['instagram_url'] ?? null, 'sort' => $sort++]);
        }

        return back()->with('saved', true);
    }

    public function destroy(GalleryItem $item): RedirectResponse
    {
        Storage::disk('public')->delete($item->image_path);
        $item->delete();

        return back()->with('saved', true);
    }
}
