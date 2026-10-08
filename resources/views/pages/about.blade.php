@php($site = config('site'))
<x-layout title="About All Things Tobago" description="The crew behind @allthingstobago: local guides, captains and hosts in Crown Point arranging tours, boats and stays across Tobago.">
    <section class="bg-ink text-white"><div class="wrap py-14 sm:py-20"><p class="eyebrow eyebrow-light">About</p><h1 class="h-section mt-3 !text-4xl sm:!text-5xl">Born on Instagram. Run from Crown Point.</h1><p class="mt-4 max-w-2xl text-white/70">{{ $site['social']['instagram_handle'] }} started as a feed of the beaches, boats and food we grew up with. {{ $site['social']['instagram_followers'] }} followers later it is a full booking service for the island, still run by people who answer the phone themselves.</p></div></section>
    <section class="section">
        <div class="wrap grid gap-12 lg:grid-cols-2 lg:items-center">
            <div class="prose-site" data-reveal>
                <p>We are a small team of guides, boat captains, therapists and hosts based in Crown Point, the corner of Tobago where the airport, Store Bay and Pigeon Point meet. Between us we cover the whole island, from the reef at Buccoo to the bird sanctuary at Speyside.</p>
                <p>What makes us different is simple. One WhatsApp number gets you a 360 tour, a boat, a jet ski, a beach massage, a villa and tickets to Sunday School, without ringing six different people and hoping they talk to each other. We put the day together, we confirm the timings, and we are on the phone if anything changes.</p>
                <h2>What we promise</h2>
                <ul>
                    <li>Honest prices in TTD, quoted before you commit, with no booking fee.</li>
                    <li>Licensed captains and insured boats on every water trip.</li>
                    <li>Weather calls made for safety, with a free date change if we cancel.</li>
                    <li>A reply within the hour during the day, every day of the week.</li>
                </ul>
            </div>
            <div class="grid gap-4 sm:grid-cols-2" data-reveal style="--reveal-delay: 100ms">
                @foreach ([['shield-check', 'Licensed and insured', 'Every captain holds a local licence and every boat carries life jackets for all guests.'], ['map', 'Whole-island coverage', 'Crown Point to Charlotteville, both coasts, by road and by sea.'], ['message-circle', 'One chat for everything', 'Tours, stays and tickets in the same WhatsApp thread, with one person who knows your plans.'], ['heart', 'Local first', 'Lunch stops, boat crews and guesthouses are all island-owned.']] as [$icon, $title, $body])
                    <div class="card !p-5"><span class="icon-tile"><x-icon :name="$icon" class="size-6" /></span><h3 class="h-card mt-4">{{ $title }}</h3><p class="mt-1.5 text-sm text-muted">{{ $body }}</p></div>
                @endforeach
            </div>
        </div>
    </section>
    @if ($reviews->isNotEmpty())
        <section class="section bg-paper-deep/60 pt-0 sm:pt-0"><div class="wrap"><div class="grid gap-5 pt-16 md:grid-cols-3 sm:pt-24">
            @foreach ($reviews as $review)<figure class="card" data-reveal><x-stars :rating="$review->rating" /><blockquote class="mt-3 text-sm leading-relaxed">&ldquo;{{ $review->body }}&rdquo;</blockquote><figcaption class="mt-3 text-xs font-bold">{{ $review->name }}</figcaption></figure>@endforeach
        </div></div></section>
    @endif
</x-layout>
