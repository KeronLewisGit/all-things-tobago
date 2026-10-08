<?php

namespace App\Support;

use App\Models\Experience;

class Content
{
    /** @var array<string, string> */
    protected static array $icons = [];

    /** Inline a Lucide icon from resources/icons. */
    public static function icon(string $name, string $class = 'size-5', string $attributes = ''): string
    {
        if (! isset(self::$icons[$name])) {
            $path = resource_path("icons/{$name}.svg");
            $svg = is_file($path) ? file_get_contents($path) : '<svg viewBox="0 0 24 24"></svg>';
            $svg = preg_replace('/<!--.*?-->/s', '', $svg);
            $svg = preg_replace_callback('/<svg\b[^>]*>/', fn (array $tag) => preg_replace('/\s(class|width|height)="[^"]*"/', '', $tag[0]), $svg, 1);
            self::$icons[$name] = trim(preg_replace('/\s+/', ' ', $svg));
        }

        return str_replace('<svg', '<svg class="'.e($class).'" aria-hidden="true" '.$attributes, self::$icons[$name]);
    }

    /** A wa.me link to the business, optionally with a pre-filled message. */
    public static function whatsapp(?string $message = null): string
    {
        return 'https://wa.me/'.config('site.whatsapp').($message ? '?text='.rawurlencode($message) : '');
    }

    /** TT$1,910 style formatting. */
    public static function ttd(int|float|null $amount): string
    {
        return 'TT$'.number_format((float) $amount, 0);
    }

    /** Approximate US$ for a TTD amount, display only. */
    public static function usd(int|float|null $amount): string
    {
        return 'US$'.number_format((float) $amount / (float) config('site.currency.usd_rate', 6.8), 0);
    }

    /** Structured data shared by every page. */
    public static function baseSchema(): array
    {
        $site = config('site');

        return [
            [
                '@type' => 'TouristInformationCenter',
                '@id' => url('/').'#business',
                'name' => $site['name'],
                'description' => $site['description'],
                'url' => url('/'),
                'telephone' => $site['phone_href'],
                'email' => $site['email'],
                'areaServed' => 'Tobago, Trinidad and Tobago',
                'address' => ['@type' => 'PostalAddress', 'addressLocality' => 'Crown Point', 'addressRegion' => 'Tobago', 'addressCountry' => 'TT'],
                'sameAs' => array_values(array_filter([$site['social']['instagram'], $site['social']['tiktok'], $site['social']['facebook']])),
            ],
            ['@type' => 'WebSite', 'url' => url('/'), 'name' => $site['name']],
        ];
    }

    public static function experienceSchema(Experience $experience): array
    {
        return [
            '@type' => 'TouristTrip',
            'name' => $experience->name,
            'description' => $experience->tagline.' '.$experience->description,
            'url' => route('experiences.show', $experience),
            'touristType' => 'Leisure',
            'itinerary' => ['@type' => 'ItemList', 'itemListElement' => array_map(fn ($h, $i) => ['@type' => 'ListItem', 'position' => $i + 1, 'name' => $h], $experience->highlights ?? [], array_keys($experience->highlights ?? []))],
            'offers' => ['@type' => 'Offer', 'price' => $experience->price, 'priceCurrency' => 'TTD', 'availability' => 'https://schema.org/InStock', 'url' => route('experiences.show', $experience)],
            'provider' => ['@id' => url('/').'#business'],
        ];
    }
}
