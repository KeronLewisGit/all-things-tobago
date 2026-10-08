<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ExperienceController extends Controller
{
    public function index(): View
    {
        return view('admin.experiences', ['experiences' => Experience::orderBy('sort')->withCount('bookings')->get(), 'categories' => config('experiences.categories')]);
    }

    public function edit(Experience $experience): View
    {
        return view('admin.experience', ['experience' => $experience, 'categories' => config('experiences.categories')]);
    }

    public function update(Request $request, Experience $experience): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'category' => ['required', Rule::in(array_keys(config('experiences.categories')))],
            'tagline' => ['nullable', 'string', 'max:160'],
            'description' => ['nullable', 'string', 'max:3000'],
            'highlights' => ['nullable', 'string', 'max:2000'],
            'includes' => ['nullable', 'string', 'max:2000'],
            'excludes' => ['nullable', 'string', 'max:2000'],
            'duration' => ['nullable', 'string', 'max:60'],
            'price' => ['required', 'integer', 'min:0', 'max:1000000'],
            'price_type' => ['required', Rule::in(['person', 'group'])],
            'min_guests' => ['required', 'integer', 'min:1', 'max:50'],
            'max_guests' => ['required', 'integer', 'min:1', 'max:60', 'gte:min_guests'],
            'child_policy' => ['nullable', 'string', 'max:160'],
            'sort' => ['required', 'integer', 'min:0', 'max:999'],
            'featured' => ['nullable', 'boolean'],
            'active' => ['nullable', 'boolean'],
            'image' => ['nullable', 'image', 'max:6144'],
            'remove_image' => ['nullable', 'boolean'],
        ]);

        foreach (['highlights', 'includes', 'excludes'] as $list) {
            $data[$list] = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', (string) ($data[$list] ?? '')))));
        }
        $data['featured'] = $request->boolean('featured');
        $data['active'] = $request->boolean('active');

        if ($request->boolean('remove_image') && $experience->image_path) {
            if (! $experience->hasBundledImage()) {
                Storage::disk('public')->delete($experience->image_path);
            }
            $data['image_path'] = null;
        }
        if ($request->hasFile('image')) {
            if ($experience->image_path && ! $experience->hasBundledImage()) {
                Storage::disk('public')->delete($experience->image_path);
            }
            $data['image_path'] = $request->file('image')->store('experiences', 'public');
        }
        unset($data['image'], $data['remove_image']);

        $experience->update($data);

        return back()->with('saved', true);
    }
}
