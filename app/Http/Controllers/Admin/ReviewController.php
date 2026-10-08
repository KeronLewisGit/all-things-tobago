<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        return view('admin.reviews', ['reviews' => Review::with('experience')->latest()->get(), 'experiences' => Experience::orderBy('name')->get(['id', 'name'])]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate(['experience_id' => ['nullable', 'exists:experiences,id'], 'name' => ['required', 'string', 'max:80'], 'origin' => ['nullable', 'string', 'max:60'], 'rating' => ['required', 'integer', 'min:1', 'max:5'], 'body' => ['required', 'string', 'max:1500']]);
        Review::create($data + ['approved' => true, 'featured' => $data['rating'] >= 5]);

        return back()->with('saved', true);
    }

    public function update(Request $request, Review $review): RedirectResponse
    {
        $review->update(['approved' => $request->boolean('approved'), 'featured' => $request->boolean('featured')]);

        return back()->with('saved', true);
    }

    public function destroy(Review $review): RedirectResponse
    {
        $review->delete();

        return back()->with('saved', true);
    }
}
