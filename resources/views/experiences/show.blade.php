@php($site = config('site'))
<x-layout :title="$experience->name" :description="$experience->tagline.' '.Str::limit($experience->description, 120)" :schema="$schema">
    <section class="relative bg-ink text-white">
        <div class="absolute inset-0 overflow-hidden">
            <x-scene :scene="$experience->scene" :image="$experience->imageUrl()" :alt="$experience->name" class="h-full w-full opacity-60" />
            <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/70 to-ink/30"></div>
        </div>
        <div class="wrap relative py-14 sm:py-24">
            <nav class="text-xs text-white/60" aria-label="Breadcrumb"><a href="{{ route('home') }}" class="hover:text-white">Home</a> / <a href="{{ route('experiences') }}" class="hover:text-white">Experiences</a> / <a href="{{ route('experiences', ['category' => $experience->category]) }}" class="hover:text-white">{{ $experience->categoryName() }}</a></nav>
            <div class="mt-6 flex flex-wrap items-center gap-2">
                <span class="chip bg-white/15 text-white ring-white/20">{{ $experience->categoryName() }}</span>
                @if ($rating)<span class="chip bg-white/15 text-white ring-white/20"><x-icon name="star" class="size-3.5 fill-sun-400 text-sun-400" /> {{ $rating }} &middot; {{ $reviews->count() }} {{ Str::plural('review', $reviews->count()) }}</span>@endif
            </div>
            <h1 class="h-display mt-4 max-w-4xl !text-4xl sm:!text-6xl">{{ $experience->name }}</h1>
            <p class="mt-4 max-w-2xl text-lg text-white/75">{{ $experience->tagline }}</p>
            <dl class="mt-8 grid max-w-2xl grid-cols-2 gap-4 sm:grid-cols-4">
                <div><dt class="text-[11px] font-bold tracking-[0.16em] text-sun-300 uppercase">From</dt><dd class="mt-1 text-xl font-bold"><x-price :amount="$experience->price" /> <span class="text-xs font-semibold text-white/60">{{ $experience->priceLabel() }}</span></dd></div>
                <div><dt class="text-[11px] font-bold tracking-[0.16em] text-sun-300 uppercase">Duration</dt><dd class="mt-1 text-sm font-semibold">{{ $experience->duration }}</dd></div>
                <div><dt class="text-[11px] font-bold tracking-[0.16em] text-sun-300 uppercase">Group</dt><dd class="mt-1 text-sm font-semibold">{{ $experience->min_guests }} to {{ $experience->max_guests }} guests</dd></div>
                <div><dt class="text-[11px] font-bold tracking-[0.16em] text-sun-300 uppercase">Children</dt><dd class="mt-1 text-sm font-semibold">{{ $experience->child_policy }}</dd></div>
            </dl>
        </div>
    </section>

    <section class="section">
        <div class="wrap grid gap-10 lg:grid-cols-[1fr_0.85fr] lg:items-start">
            <div class="space-y-10">
                <div class="prose-site" data-reveal>
                    <p>{{ $experience->description }}</p>
                </div>
                <div class="grid gap-5 sm:grid-cols-2" data-reveal>
                    <div class="card !p-6">
                        <h2 class="h-card flex items-center gap-2"><x-icon name="sparkles" class="size-5 text-coral-500" /> Highlights</h2>
                        <ul class="mt-4 space-y-2.5 text-sm">
                            @foreach ($experience->highlights ?? [] as $h)<li class="flex gap-2.5"><x-icon name="check" class="mt-0.5 size-4 shrink-0 text-sea-600" /> {{ $h }}</li>@endforeach
                        </ul>
                    </div>
                    <div class="card !p-6">
                        <h2 class="h-card flex items-center gap-2"><x-icon name="list-checks" class="size-5 text-sea-600" /> What's included</h2>
                        <ul class="mt-4 space-y-2.5 text-sm">
                            @foreach ($experience->includes ?? [] as $h)<li class="flex gap-2.5"><x-icon name="check" class="mt-0.5 size-4 shrink-0 text-sea-600" /> {{ $h }}</li>@endforeach
                            @foreach ($experience->excludes ?? [] as $h)<li class="flex gap-2.5 text-muted"><x-icon name="x" class="mt-0.5 size-4 shrink-0 text-rose-400" /> {{ $h }} <span class="text-xs">(not included)</span></li>@endforeach
                        </ul>
                    </div>
                </div>
                <div class="rounded-3xl bg-sea-50 p-6 ring-1 ring-sea-200" data-reveal>
                    <h2 class="h-card">Good to know</h2>
                    <ul class="mt-3 grid gap-2 text-sm sm:grid-cols-2">
                        <li class="flex gap-2"><x-icon name="map-pin" class="mt-0.5 size-4 shrink-0 text-sea-700" /> {{ $site['pickup_note'] }}</li>
                        <li class="flex gap-2"><x-icon name="umbrella" class="mt-0.5 size-4 shrink-0 text-sea-700" /> Bring swimwear, a towel and reef-safe sunscreen. We supply the gear.</li>
                        <li class="flex gap-2"><x-icon name="cloud-rain" class="mt-0.5 size-4 shrink-0 text-sea-700" /> If the captain calls off a trip for weather, you move the date or get any deposit back.</li>
                        <li class="flex gap-2"><x-icon name="receipt" class="mt-0.5 size-4 shrink-0 text-sea-700" /> Pay cash in TTD or USD, bank transfer, or a card payment link.</li>
                    </ul>
                </div>
                @if ($reviews->isNotEmpty())
                    <div data-reveal>
                        <h2 class="h-section !text-2xl">What guests said</h2>
                        <div class="mt-5 grid gap-4 sm:grid-cols-2">
                            @foreach ($reviews as $review)
                                <figure class="card !p-5"><x-stars :rating="$review->rating" /><blockquote class="mt-3 text-sm leading-relaxed">&ldquo;{{ $review->body }}&rdquo;</blockquote><figcaption class="mt-3 text-xs font-bold">{{ $review->name }}@if ($review->origin)<span class="font-normal text-muted"> &middot; {{ $review->origin }}</span>@endif</figcaption></figure>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="flex flex-wrap items-center gap-3" x-data="share(@js($experience->name), @js(route('experiences.show', $experience)))">
                    <button type="button" class="btn btn-light" @click="go()"><x-icon name="share-2" class="size-4" /> <span x-text="copied ? 'Link copied' : 'Share this trip'">Share this trip</span></button>
                    <a href="{{ \App\Support\Content::whatsapp('Hi! I have a question about '.$experience->name.' ('.route('experiences.show', $experience).')') }}" target="_blank" rel="noopener" class="btn btn-whatsapp"><x-icon name="message-circle" class="size-4" /> Ask a question</a>
                </div>
            </div>

            {{-- Booking widget --}}
            <div id="book" class="lg:sticky lg:top-28" x-data="booking(@js(['price' => $experience->price, 'priceType' => $experience->price_type, 'min' => $experience->min_guests, 'max' => $experience->max_guests, 'blackouts' => $blackouts, 'adults' => (int) old('adults', 2), 'children' => (int) old('children', 0), 'date' => old('date', ''), 'slot' => old('time_slot', 'morning')]))">
                <x-form-status bag="booking" key="booking_success" />
                <form method="post" action="{{ route('bookings.store', $experience) }}" class="card space-y-5 !p-6">
                    @csrf
                    <div class="flex items-start justify-between gap-4">
                        <div><p class="text-[11px] font-bold tracking-[0.16em] text-muted uppercase">Book this</p><h2 class="h-card mt-1">{{ $experience->name }}</h2></div>
                        <p class="text-right"><span class="block text-[10px] font-bold tracking-wider text-muted uppercase">Estimate</span><span class="font-display text-2xl font-bold tabular-nums" x-text="estimateLabel">{{ \App\Support\Content::ttd($experience->estimate(2)) }}</span></p>
                    </div>
                    <div>
                        <p class="label flex items-center justify-between">Date <span class="text-xs font-semibold text-sea-700" x-text="dateLabel"></span></p>
                        <x-calendar />
                        <noscript><input type="date" name="date" class="input mt-2" min="{{ today()->toDateString() }}" required></noscript>
                        <input type="hidden" name="date" :value="date">
                        @error('date', 'booking')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <p class="label">Time of day</p>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach (\App\Models\Booking::SLOTS as $key => $label)
                                <label class="option"><input type="radio" name="time_slot" value="{{ $key }}" class="peer sr-only" x-model="slot"><span class="!justify-center !px-2 !text-xs">{{ Str::before($label, ' (') }}</span></label>
                            @endforeach
                        </div>
                    </div>
                    <div class="space-y-2">
                        <x-stepper model="adults" label="Adults" :floor="1" />
                        <x-stepper model="children" label="Children" hint="Half price on per-person trips" />
                        <p class="hint" x-show="guests >= max" x-cloak>Maximum {{ $experience->max_guests }} for this experience. Message us for bigger groups.</p>
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-field name="name" label="Your name" bag="booking" required autocomplete="name" />
                        <x-field name="phone" label="WhatsApp number" type="tel" bag="booking" required autocomplete="tel" placeholder="+1 868 000 0000" />
                        <x-field name="email" label="Email" type="email" bag="booking" autocomplete="email" hint="Optional, for a written confirmation." />
                        <x-field name="pickup" label="Pickup area" type="select" bag="booking" :options="array_combine($site['pickup_areas'], $site['pickup_areas'])" placeholder="Choose" />
                    </div>
                    <x-field name="notes" label="Anything we should know?" type="textarea" bag="booking" placeholder="Birthday, proposal, non-swimmers, dietary needs..." />
                    <x-consent bag="booking" />
                    <button class="btn btn-coral w-full !py-3.5">Send booking request <x-icon name="arrow-right" class="size-4" /></button>
                    <p class="text-center text-xs text-muted">No payment now. We confirm availability first.</p>
                </form>
            </div>
        </div>
    </section>

    @if ($related->isNotEmpty())
        <section class="section bg-paper-deep/60">
            <div class="wrap">
                <h2 class="h-section !text-2xl" data-reveal>Pairs well with</h2>
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($related as $exp)<x-experience-card :experience="$exp" :delay="$loop->index * 60" />@endforeach
                </div>
            </div>
        </section>
    @endif
</x-layout>
