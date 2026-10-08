@props(['title' => null, 'description' => null, 'schema' => [], 'dark' => false])
@php
    $site = config('site');
    $pageTitle = $title ? $title.' · '.$site['name'] : $site['name'].' · '.$site['tagline'];
    $description = $description ?? $site['description'];
    $canonical = url()->current();
    $whatsapp = \App\Support\Content::whatsapp('Hi All Things Tobago! I found you on your website and would like to book something.');
    $categories = config('experiences.categories');
    $current = request()->route()?->getName() ?? '';
    $links = [
        ['experiences', 'Experiences', ['experiences', 'experiences.show']],
        ['planner', 'Plan your day', ['planner']],
        ['stay', 'Stay', ['stay']],
        ['events', 'Events', ['events']],
        ['about', 'About', ['about']],
        ['contact', 'Contact', ['contact']],
    ];
@endphp
<!DOCTYPE html>
<html lang="en" data-usd-rate="{{ $site['currency']['usd_rate'] }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $pageTitle }}</title>
    <meta name="description" content="{{ $description }}">
    <meta name="keywords" content="{{ implode(', ', $site['seo']['keywords']) }}">
    <link rel="canonical" href="{{ $canonical }}">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="{{ $site['name'] }}">
    <meta property="og:title" content="{{ $pageTitle }}">
    <meta property="og:description" content="{{ $description }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:image" content="{{ asset('images/og.png') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="theme-color" content="#06212f">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/mark.svg') }}">
    <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.png') }}">
    <script type="application/ld+json">{!! json_encode(['@context' => 'https://schema.org', '@graph' => array_merge(\App\Support\Content::baseSchema(), $schema)], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col" x-data="{ menu: false }" :class="menu && 'overflow-hidden'" @keydown.escape.window="menu = false">
    <a href="#main" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[60] focus:rounded-full focus:bg-white focus:px-4 focus:py-2 focus:text-sm focus:font-semibold">Skip to content</a>

    {{-- Utility bar --}}
    <div class="bg-ink text-white">
        <div class="wrap flex h-10 items-center justify-between gap-4 text-xs">
            <p class="flex min-w-0 items-center gap-2 text-white/70">
                <x-icon name="instagram" class="size-3.5 shrink-0 text-sun-300" />
                <a href="{{ $site['social']['instagram'] }}" target="_blank" rel="noopener" class="truncate hover:text-white">{{ $site['social']['instagram_handle'] }} <span class="hidden sm:inline">&middot; {{ $site['social']['instagram_followers'] }} followers &middot; {{ $site['base'] }}</span></a>
            </p>
            <div class="flex shrink-0 items-center gap-4">
                <button type="button" x-data class="hidden items-center gap-1 rounded-full bg-white/10 px-2.5 py-1 font-bold ring-1 ring-white/15 hover:bg-white/20 sm:flex" @click="$store.currency.toggle()" aria-label="Switch currency">
                    <span x-text="$store.currency.code">TTD</span> <x-icon name="chevron-down" class="size-3" />
                </button>
                <a href="tel:{{ $site['phone_href'] }}" class="hidden items-center gap-1.5 font-semibold transition hover:text-sun-300 md:flex"><x-icon name="phone" class="size-3.5" /> {{ $site['phone'] }}</a>
                <a href="{{ $whatsapp }}" target="_blank" rel="noopener" class="flex items-center gap-1 font-semibold transition hover:text-sun-300">WhatsApp us <x-icon name="arrow-up-right" class="size-3.5" /></a>
            </div>
        </div>
    </div>

    {{-- Header --}}
    <header class="sticky top-0 z-40 border-b border-line/80 bg-paper/90 backdrop-blur-lg">
        <div class="wrap flex h-[4.5rem] items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="shrink-0" aria-label="All Things Tobago home"><x-logo /></a>
            <nav class="hidden items-center gap-1 lg:flex" aria-label="Main">
                @foreach ($links as [$route, $label, $active])
                    <a href="{{ route($route) }}" class="rounded-full px-3.5 py-2 text-sm font-semibold transition hover:bg-ink/5 {{ in_array($current, $active) ? 'text-sea-700' : '' }}">{{ $label }}</a>
                @endforeach
            </nav>
            <div class="flex items-center gap-2">
                <a href="{{ route('experiences') }}" class="btn btn-coral hidden !py-2.5 sm:inline-flex">Book now</a>
                <button type="button" class="grid size-10 place-items-center rounded-full bg-ink text-white lg:hidden" @click="menu = true" aria-label="Open menu"><x-icon name="menu" class="size-[18px]" /></button>
            </div>
        </div>
    </header>

    {{-- Mobile menu --}}
    <div x-cloak x-show="menu" x-transition.opacity class="fixed inset-0 z-50 bg-ink text-white lg:hidden" role="dialog" aria-modal="true" aria-label="Menu">
        <div class="flex h-full flex-col overflow-y-auto">
            <div class="flex h-[4.5rem] shrink-0 items-center justify-between px-5">
                <x-logo light />
                <button type="button" class="grid size-10 place-items-center rounded-full bg-white/10" @click="menu = false" aria-label="Close menu"><x-icon name="x" class="size-5" /></button>
            </div>
            <nav class="flex-1 px-5 pt-2 pb-10" aria-label="Mobile">
                <a href="{{ route('experiences') }}" class="block py-3 font-display text-2xl font-bold">All experiences</a>
                <div class="mt-1 grid grid-cols-2 gap-x-4">
                    @foreach ($categories as $key => $cat)
                        <a href="{{ route('experiences', ['category' => $key]) }}" class="flex items-center gap-2 py-2 text-sm text-white/80"><x-icon :name="$cat['icon']" class="size-4 text-sun-300" /> {{ $cat['name'] }}</a>
                    @endforeach
                </div>
                <div class="my-5 h-px bg-white/10"></div>
                @foreach (array_slice($links, 1) as [$route, $label])
                    <a href="{{ route($route) }}" class="block py-3 font-display text-2xl font-bold">{{ $label }}</a>
                @endforeach
                <div class="mt-6 flex flex-col gap-3">
                    <a href="{{ route('planner') }}" class="btn btn-sun">Plan your day</a>
                    <a href="{{ $whatsapp }}" class="btn btn-whatsapp" target="_blank" rel="noopener"><x-icon name="message-circle" class="size-4" /> WhatsApp {{ $site['phone'] }}</a>
                </div>
                <button type="button" x-data class="mt-6 text-sm text-white/60" @click="$store.currency.toggle()">Prices in <span class="font-bold text-white" x-text="$store.currency.code">TTD</span> · tap to switch</button>
            </nav>
        </div>
    </div>

    <main id="main" class="flex-1">{{ $slot }}</main>

    {{-- Footer --}}
    <footer class="bg-ink text-white">
        <div class="h-1 bg-gradient-to-r from-sea-500 via-sun-400 to-coral-500"></div>
        <div class="wrap grid gap-12 py-16 lg:grid-cols-[1.3fr_1fr_1fr_1fr]">
            <div>
                <x-logo light />
                <p class="mt-5 max-w-sm text-sm leading-relaxed text-white/60">{{ $site['intro'] }}</p>
                <div class="mt-6 space-y-2 text-sm">
                    <a href="tel:{{ $site['phone_href'] }}" class="flex items-center gap-2.5 text-white/80 hover:text-white"><x-icon name="phone" class="size-4 text-sun-300" /> {{ $site['phone'] }}</a>
                    <a href="mailto:{{ $site['email'] }}" class="flex items-center gap-2.5 text-white/80 hover:text-white"><x-icon name="mail" class="size-4 shrink-0 text-sun-300" /> <span class="break-all">{{ $site['email'] }}</span></a>
                    <p class="flex items-center gap-2.5 text-white/60"><x-icon name="map-pin" class="size-4 text-sun-300" /> {{ $site['base'] }}</p>
                    <p class="flex items-center gap-2.5 text-white/60"><x-icon name="clock" class="size-4 text-sun-300" /> {{ $site['hours'] }}</p>
                </div>
                <div class="mt-6 flex gap-2">
                    <a href="{{ $site['social']['instagram'] }}" target="_blank" rel="noopener" class="grid size-10 place-items-center rounded-full bg-white/10 hover:bg-white/20" aria-label="Instagram"><x-icon name="instagram" class="size-4" /></a>
                    <a href="{{ $site['social']['tiktok'] }}" target="_blank" rel="noopener" class="grid size-10 place-items-center rounded-full bg-white/10 hover:bg-white/20" aria-label="TikTok"><x-icon name="tiktok" class="size-4" /></a>
                    <a href="{{ $site['social']['facebook'] }}" target="_blank" rel="noopener" class="grid size-10 place-items-center rounded-full bg-white/10 hover:bg-white/20" aria-label="Facebook"><x-icon name="facebook" class="size-4" /></a>
                </div>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-[0.18em] text-sun-300 uppercase">Experiences</p>
                <ul class="mt-4 space-y-2.5 text-sm text-white/75">
                    @foreach ($categories as $key => $cat)
                        <li><a href="{{ route('experiences', ['category' => $key]) }}" class="hover:text-white">{{ $cat['name'] }}</a></li>
                    @endforeach
                    <li><a href="{{ route('planner') }}" class="font-semibold text-sun-300 hover:text-white">Plan your day</a></li>
                </ul>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-[0.18em] text-sun-300 uppercase">More</p>
                <ul class="mt-4 space-y-2.5 text-sm text-white/75">
                    <li><a href="{{ route('stay') }}" class="hover:text-white">Places to stay</a></li>
                    <li><a href="{{ route('events') }}" class="hover:text-white">Events and tickets</a></li>
                    <li><a href="{{ route('about') }}" class="hover:text-white">About the crew</a></li>
                    <li><a href="{{ route('faq') }}" class="hover:text-white">Questions answered</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-white">Contact</a></li>
                    <li><a href="{{ route('links') }}" class="hover:text-white">Link in bio</a></li>
                </ul>
            </div>
            <div>
                <p class="text-[11px] font-bold tracking-[0.18em] text-sun-300 uppercase">Good to know</p>
                <ul class="mt-4 space-y-2.5 text-sm text-white/75">
                    <li>{{ $site['pickup_note'] }}</li>
                    <li><a href="{{ route('terms') }}" class="hover:text-white">Booking terms</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-white">Privacy</a></li>
                    <li><a href="{{ route('photo-credits') }}" class="hover:text-white">Photo credits</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-white/10">
            <div class="wrap flex flex-col gap-3 py-6 text-xs text-white/50 sm:flex-row sm:items-center sm:justify-between">
                <p>&copy; {{ date('Y') }} {{ $site['name'] }}. Crown Point, Tobago. Prices in TTD; US$ figures are approximate.</p>
                <p>Licensed local operator. Tours run subject to weather and sea conditions.</p>
            </div>
        </div>
    </footer>

    {{-- Floating WhatsApp --}}
    <a href="{{ $whatsapp }}" target="_blank" rel="noopener" class="fixed right-4 bottom-4 z-30 grid size-14 place-items-center rounded-full bg-emerald-600 text-white shadow-lift ring-4 ring-white/70 transition hover:scale-105 sm:right-6 sm:bottom-6" aria-label="Chat on WhatsApp"><x-icon name="message-circle" class="size-6" /></a>
</body>
</html>
