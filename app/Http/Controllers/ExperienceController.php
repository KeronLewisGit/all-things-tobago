<?php

namespace App\Http\Controllers;

use App\Models\BlackoutDate;
use App\Models\Experience;
use App\Support\Content;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExperienceController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->query('category');
        $categories = config('experiences.categories');
        abort_if($category && ! isset($categories[$category]), 404);

        $query = Experience::active()->withCount(['reviews' => fn ($q) => $q->where('approved', true)])->withAvg(['reviews' => fn ($q) => $q->where('approved', true)], 'rating');

        return view('experiences.index', [
            'experiences' => $category ? $query->where('category', $category)->get() : $query->get(),
            'category' => $category,
            'categories' => $categories,
        ]);
    }

    public function show(Experience $experience): View
    {
        abort_unless($experience->active, 404);

        return view('experiences.show', [
            'experience' => $experience,
            'reviews' => $experience->reviews()->where('approved', true)->latest()->take(6)->get(),
            'rating' => round((float) $experience->reviews()->where('approved', true)->avg('rating'), 1),
            'related' => Experience::active()->where('id', '!=', $experience->id)->where('category', $experience->category)->take(3)->get()
                ->whenEmpty(fn () => Experience::active()->where('id', '!=', $experience->id)->where('featured', true)->take(3)->get()),
            'blackouts' => BlackoutDate::whereDate('date', '>=', today())->pluck('date')->map->toDateString()->all(),
            'schema' => [Content::experienceSchema($experience)],
        ]);
    }
}
