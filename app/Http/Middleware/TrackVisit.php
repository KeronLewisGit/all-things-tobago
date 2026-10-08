<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/** First-touch attribution kept in the session, so every booking knows where the guest came from. */
class TrackVisit
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') && ! $request->is('admin*', 'sitemap.xml', 'robots.txt', 'up') && ! $request->session()->has('attribution')) {
            $referrer = (string) $request->headers->get('referer', '');
            $host = $referrer ? parse_url($referrer, PHP_URL_HOST) : null;
            $request->session()->put('attribution', [
                'utm_source' => Str::limit((string) $request->query('utm_source', ''), 80, '') ?: ($host && str_contains($host, 'instagram') ? 'instagram' : null),
                'utm_medium' => Str::limit((string) $request->query('utm_medium', ''), 80, '') ?: null,
                'utm_campaign' => Str::limit((string) $request->query('utm_campaign', ''), 120, '') ?: null,
                'referrer_host' => $host && $host !== $request->getHost() ? $host : null,
                'landing_page' => $request->path(),
            ]);
        }

        return $next($request);
    }
}
