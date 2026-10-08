@php($site = config('site'))
<x-layout title="Links" description="All Things Tobago link in bio: book tours, boats and water sports, find a stay, get tickets, WhatsApp the crew.">
    <section class="relative min-h-screen overflow-hidden bg-ink py-10 text-white">
        <div class="absolute inset-0 bg-gradient-to-b from-sea-900 via-ink to-ink"></div>
        <div class="absolute -top-20 left-1/2 h-80 w-80 -translate-x-1/2 rounded-full bg-sea-500/30 blur-3xl"></div>
        <div class="relative mx-auto w-full max-w-md px-5">
            <div class="text-center">
                <span class="mx-auto grid size-20 place-items-center rounded-3xl bg-sea-600 text-white shadow-glow ring-4 ring-sun-400/70"><x-icon name="palmtree" class="size-10" /></span>
                <h1 class="mt-4 font-display text-2xl font-bold">{{ $site['name'] }}</h1>
                <p class="mt-1 text-sm text-white/70">{{ $site['social']['instagram_handle'] }} &middot; {{ $site['base'] }}</p>
                @if ($weather)<p class="mt-2 inline-flex items-center gap-1.5 rounded-full bg-white/10 px-3 py-1 text-xs"><x-icon :name="$weather['icon']" class="size-3.5 text-sun-300" /> {{ $weather['temp'] }}°C in Tobago right now</p>@endif
            </div>
            <div class="mt-8 space-y-3">
                <a href="{{ \App\Support\Content::whatsapp('Hi All Things Tobago! I came from your link in bio.') }}" target="_blank" rel="noopener" class="btn btn-whatsapp w-full !py-4 !text-base"><x-icon name="message-circle" class="size-5" /> WhatsApp to book</a>
                <a href="{{ route('experiences') }}" class="btn btn-light w-full !py-4 !text-base"><x-icon name="compass" class="size-5" /> All tours and activities</a>
                <a href="{{ route('planner') }}" class="btn btn-sun w-full !py-4 !text-base"><x-icon name="sparkles" class="size-5" /> Plan a full day</a>
                <a href="{{ route('stay') }}" class="btn btn-onDark w-full !py-4 !text-base"><x-icon name="bed-double" class="size-5" /> Find a place to stay</a>
                <a href="{{ route('events') }}" class="btn btn-onDark w-full !py-4 !text-base"><x-icon name="ticket" class="size-5" /> Events and tickets</a>
            </div>
            <p class="mt-8 text-center text-[11px] font-bold tracking-[0.2em] text-sun-300 uppercase">Popular this week</p>
            <div class="mt-3 space-y-3">
                @foreach ($featured as $exp)
                    <a href="{{ route('experiences.show', $exp) }}" class="flex items-center gap-3 rounded-2xl bg-white/10 p-3 ring-1 ring-white/10 hover:bg-white/15">
                        <span class="size-14 shrink-0 overflow-hidden rounded-xl"><x-scene :scene="$exp->scene" :image="$exp->imageUrl()" :alt="$exp->name" /></span>
                        <span class="min-w-0 flex-1"><span class="block truncate text-sm font-bold">{{ $exp->name }}</span><span class="block text-xs text-white/60">from <x-price :amount="$exp->price" /> &middot; {{ $exp->duration }}</span></span>
                        <x-icon name="chevron-right" class="size-4 text-white/50" />
                    </a>
                @endforeach
            </div>
            <div class="mt-8 flex justify-center gap-3">
                <a href="{{ $site['social']['instagram'] }}" target="_blank" rel="noopener" class="grid size-11 place-items-center rounded-full bg-white/10" aria-label="Instagram"><x-icon name="instagram" class="size-5" /></a>
                <a href="{{ $site['social']['tiktok'] }}" target="_blank" rel="noopener" class="grid size-11 place-items-center rounded-full bg-white/10" aria-label="TikTok"><x-icon name="tiktok" class="size-5" /></a>
                <a href="{{ $site['social']['facebook'] }}" target="_blank" rel="noopener" class="grid size-11 place-items-center rounded-full bg-white/10" aria-label="Facebook"><x-icon name="facebook" class="size-5" /></a>
            </div>
            <p class="mt-6 text-center text-xs text-white/40">Your own link in bio, on your own domain. No Linktree, no ads.</p>
        </div>
    </section>
</x-layout>
