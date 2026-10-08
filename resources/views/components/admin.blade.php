@props(['title' => 'Dashboard'])
@php
    $current = request()->route()?->getName() ?? '';
    $nav = [
        ['admin.dashboard', 'Dashboard', 'layout-dashboard', ['admin.dashboard']],
        ['admin.bookings', 'Bookings', 'inbox', ['admin.bookings', 'admin.bookings.show']],
        ['admin.calendar', 'Calendar', 'calendar-days', ['admin.calendar']],
        ['admin.experiences', 'Experiences', 'compass', ['admin.experiences', 'admin.experiences.edit']],
        ['admin.reviews', 'Reviews', 'star', ['admin.reviews']],
        ['admin.gallery', 'Gallery', 'image', ['admin.gallery']],
        ['admin.enquiries', 'Enquiries', 'mail', ['admin.enquiries']],
    ];
    $newCount = \App\Models\Booking::where('status', 'new')->count();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} · ATT bookings</title>
    <meta name="robots" content="noindex">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/mark.svg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-paper-deep/60 lg:flex">
    <aside class="flex shrink-0 flex-col bg-ink text-white lg:sticky lg:top-0 lg:h-screen lg:w-64">
        <div class="flex items-center justify-between gap-3 p-5">
            <a href="{{ route('admin.dashboard') }}"><x-logo light compact /></a>
            <span class="rounded-full bg-white/10 px-2.5 py-1 text-[10px] font-bold tracking-wider uppercase">Bookings</span>
        </div>
        <nav class="flex flex-wrap gap-1 px-3 pb-3 lg:flex-1 lg:flex-col lg:flex-nowrap lg:pb-0" aria-label="Admin">
            @foreach ($nav as [$route, $label, $icon, $active])
                <a href="{{ route($route) }}" @class(['flex shrink-0 items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold transition', 'bg-white text-ink' => in_array($current, $active), 'text-white/70 hover:bg-white/10 hover:text-white' => ! in_array($current, $active)])>
                    <x-icon :name="$icon" class="size-[18px]" /> {{ $label }}
                    @if ($route === 'admin.bookings' && $newCount)<span class="ml-auto rounded-full bg-coral-500 px-2 py-0.5 text-[11px] font-bold text-white">{{ $newCount }}</span>@endif
                </a>
            @endforeach
        </nav>
        <div class="hidden border-t border-white/10 p-3 lg:block">
            <a href="{{ route('home') }}" target="_blank" class="flex items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-white/70 hover:bg-white/10 hover:text-white"><x-icon name="external-link" class="size-[18px]" /> View website</a>
            <form method="post" action="{{ route('admin.logout') }}">@csrf<button class="flex w-full items-center gap-3 rounded-xl px-3.5 py-2.5 text-sm font-semibold text-white/70 hover:bg-white/10 hover:text-white"><x-icon name="log-out" class="size-[18px]" /> Sign out</button></form>
            <p class="px-3.5 pt-2 text-xs text-white/40">{{ auth()->user()->name }}</p>
        </div>
    </aside>
    <main class="min-w-0 flex-1 p-5 sm:p-8 lg:p-10">
        @if (session('saved'))
            <div class="mb-6 flex items-center gap-3 rounded-2xl bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-900 ring-1 ring-emerald-200" role="status"><x-icon name="check" class="size-4" /> Saved.</div>
        @endif
        @if ($errors->any())
            <div class="mb-6 rounded-2xl bg-rose-50 px-4 py-3 text-sm text-rose-900 ring-1 ring-rose-200" role="alert">{{ $errors->first() }}</div>
        @endif
        {{ $slot }}
        <form method="post" action="{{ route('admin.logout') }}" class="mt-10 lg:hidden">@csrf<button class="btn btn-light">Sign out</button></form>
    </main>
</body>
</html>
