<?php

namespace App\Http\Controllers;

use App\Models\Experience;
use Illuminate\Http\Response;

class SeoController extends Controller
{
    public function sitemap(): Response
    {
        $urls = collect(['home', 'experiences', 'planner', 'stay', 'events', 'about', 'faq', 'contact', 'links'])->map(fn ($r) => ['loc' => route($r), 'priority' => $r === 'home' ? '1.0' : '0.7'])
            ->concat(Experience::active()->get()->map(fn ($e) => ['loc' => route('experiences.show', $e), 'priority' => '0.9', 'lastmod' => $e->updated_at?->toDateString()]));

        return response()->view('sitemap', ['urls' => $urls])->header('Content-Type', 'application/xml');
    }

    public function robots(): Response
    {
        return response("User-agent: *\nAllow: /\nDisallow: /admin\nSitemap: ".route('sitemap')."\n")->header('Content-Type', 'text/plain');
    }
}
