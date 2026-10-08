<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use App\Models\GalleryItem;
use App\Models\Review;
use App\Support\Weather;
use Illuminate\View\View;

class PageController extends Controller
{
    public function home(): View
    {
        return view('pages.home', [
            'featured' => Experience::active()->where('featured', true)->take(4)->get(),
            'experiences' => Experience::active()->get(),
            'reviews' => Review::approved()->where('featured', true)->with('experience')->take(6)->get(),
            'gallery' => GalleryItem::orderBy('sort')->take(8)->get(),
            'weather' => Weather::today(),
            'categories' => config('experiences.categories'),
        ]);
    }

    public function about(): View
    {
        return view('pages.about', ['reviews' => Review::approved()->take(3)->get()]);
    }

    public function faq(): View
    {
        return view('pages.faq');
    }

    public function contact(): View
    {
        return view('pages.contact');
    }

    public function stay(): View
    {
        return view('pages.stay');
    }

    public function events(): View
    {
        return view('pages.events');
    }

    /** Link-in-bio page: replaces Linktree on the client's own domain. */
    public function links(): View
    {
        return view('pages.links', ['featured' => Experience::active()->where('featured', true)->take(3)->get(), 'weather' => Weather::today()]);
    }

    public function privacy(): View
    {
        return view('pages.privacy');
    }

    public function terms(): View
    {
        return view('pages.terms');
    }

    public function photoCredits(): View
    {
        $credits = config('experiences.photo_credits', []);
        $experiences = Experience::query()->whereIn('slug', array_keys($credits))->orderBy('sort')->get()
            ->filter(fn (Experience $e) => $e->hasBundledImage());

        return view('pages.photo-credits', ['experiences' => $experiences, 'credits' => $credits]);
    }
}
