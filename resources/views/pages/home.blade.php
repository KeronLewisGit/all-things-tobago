@php($site = config('site'))
<x-layout>
    {{-- Hero --}}
    <section class="relative overflow-hidden bg-ink text-white">
        <div class="absolute inset-0 bg-gradient-to-br from-sea-900 via-ink to-ink"></div>
        <div class="absolute inset-0 grid-dots opacity-60"></div>
        <div class="absolute -top-40 right-0 h-[40rem] w-[40rem] rounded-full bg-sea-500/30 blur-3xl"></div>
        <div class="absolute -bottom-40 -left-20 h-[30rem] w-[30rem] rounded-full bg-coral-500/25 blur-3xl"></div>
        <div class="wrap relative grid gap-12 py-16 sm:py-24 lg:grid-cols-[1.1fr_0.9fr] lg:items-center lg:py-28">
            <div data-reveal>
                <p class="eyebrow eyebrow-light">Crown Point, Tobago &middot; {{ $site['social']['instagram_followers'] }} on Instagram</p>
                <h1 class="h-display mt-5">Every tour, every boat, every beach. <span class="bg-gradient-to-r from-sea-300 via-sun-300 to-coral-400 bg-clip-text text-transparent">One island, one call.</span></h1>
                <p class="mt-6 max-w-xl text-lg leading-relaxed text-white/75">{{ $site['intro'] }} Pick a date, tell us who is coming, and the crew behind {{ $site['social']['instagram_handle'] }} does the rest.</p>
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ route('experiences') }}" class="btn btn-coral">Browse experiences <x-icon name="arrow-right" class="size-4" /></a>
                    <a href="{{ route('planner') }}" class="btn btn-onDark"><x-icon name="sparkles" class="size-4" /> Plan a full day</a>
                </div>
                <dl class="mt-10 grid max-w-lg grid-cols-3 gap-4 border-t border-white/10 pt-6">
                    <div><dt class="text-[11px] font-bold tracking-[0.16em] text-sun-300 uppercase">Reply time</dt><dd class="mt-1 text-sm font-semibold">Within the hour</dd></div>
                    <div><dt class="text-[11px] font-bold tracking-[0.16em] text-sun-300 uppercase">Pickup</dt><dd class="mt-1 text-sm font-semibold">Free in Crown Point</dd></div>
                    <div><dt class="text-[11px] font-bold tracking-[0.16em] text-sun-300 uppercase">Pay</dt><dd class="mt-1 text-sm font-semibold">Cash, transfer or card link</dd></div>
                </dl>
            </div>
            <div class="relative mx-auto w-full max-w-md lg:max-w-none" data-reveal style="--reveal-delay: 120ms">
                <div class="grid grid-cols-2 gap-3 sm:gap-4">
                    @foreach ($featured->take(4) as $exp)
                        <a href="{{ route('experiences.show', $exp) }}" class="group relative aspect-[4/5] overflow-hidden rounded-[1.75rem] ring-1 ring-white/10 {{ $loop->index === 1 ? 'translate-y-8' : '' }} {{ $loop->index === 2 ? '-translate-y-4' : '' }}">
                            <x-scene :scene="$exp->scene" :image="$exp->imageUrl()" :alt="$exp->name" class="h-full w-full transition duration-700 group-hover:scale-105" />
                            <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-ink/90 to-transparent p-4 pt-12">
                                <p class="line-clamp-2 font-display text-base font-bold leading-tight">{{ $exp->name }}</p>
                                <p class="mt-1 text-xs text-white/70">from <x-price :amount="$exp->price" /></p>
                            </div>
                        </a>
                    @endforeach
                </div>
                @if ($weather)
                    <div class="absolute -bottom-6 left-1/2 flex w-[92%] -translate-x-1/2 items-center gap-4 rounded-2xl bg-white p-4 text-ink shadow-lift animate-float">
                        <span class="grid size-11 shrink-0 place-items-center rounded-xl bg-sun-100 text-sun-600"><x-icon :name="$weather['icon']" class="size-6" /></span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[10px] font-bold tracking-[0.16em] text-muted uppercase">Tobago right now</p>
                            <p class="truncate text-sm font-bold">{{ $weather['temp'] }}°C, {{ strtolower($weather['label']) }}@if ($weather['sea']) &middot; sea {{ $weather['sea'] }}°C @endif</p>
                        </div>
                        <p class="hidden text-right text-xs text-muted sm:block">Sunset<br><span class="font-bold text-ink">{{ $weather['sunset'] }}</span></p>
                    </div>
                @endif
            </div>
        </div>
        {{-- Marquee --}}
        <div class="relative border-t border-white/10 bg-ink/60 py-3 backdrop-blur">
            <div class="flex w-max animate-marquee gap-10 text-[11px] font-bold tracking-[0.2em] whitespace-nowrap text-white/70 uppercase">
                @for ($i = 0; $i < 2; $i++)
                    @foreach ($experiences as $exp)
                        <span class="flex items-center gap-2"><x-icon :name="$categories[$exp->category]['icon']" class="size-3.5 text-sun-300" /> {{ $exp->name }}</span>
                    @endforeach
                @endfor
            </div>
        </div>
    </section>

    {{-- Categories --}}
    <section class="section">
        <div class="wrap">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between" data-reveal>
                <div><p class="eyebrow">What do you feel like?</p><h2 class="h-section mt-3">Five ways to do Tobago.</h2></div>
                <a href="{{ route('experiences') }}" class="link-arrow">All {{ $experiences->count() }} experiences <x-icon name="arrow-right" class="size-4" /></a>
            </div>
            <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-5">
                @foreach ($categories as $key => $cat)
                    <a href="{{ route('experiences', ['category' => $key]) }}" class="group card !p-5 transition hover:-translate-y-1 hover:shadow-lift" data-reveal style="--reveal-delay: {{ $loop->index * 60 }}ms">
                        <span class="icon-tile group-hover:bg-sea-600 group-hover:text-white"><x-icon :name="$cat['icon']" class="size-6" /></span>
                        <h3 class="h-card mt-4">{{ $cat['name'] }}</h3>
                        <p class="mt-1.5 text-sm text-muted">{{ $cat['blurb'] }}</p>
                        <p class="mt-3 text-xs font-bold text-sea-700">{{ $experiences->where('category', $key)->count() }} {{ Str::plural('option', $experiences->where('category', $key)->count()) }}</p>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Featured --}}
    <section class="section bg-paper-deep/60 pt-0 sm:pt-0">
        <div class="wrap">
            <div class="pt-16 sm:pt-24" data-reveal><p class="eyebrow">Most booked</p><h2 class="h-section mt-3">The trips people fly here for.</h2></div>
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($featured as $exp)
                    <x-experience-card :experience="$exp" :delay="$loop->index * 70" />
                @endforeach
            </div>
        </div>
    </section>

    {{-- How it works + planner pitch --}}
    <section class="section">
        <div class="wrap grid gap-12 lg:grid-cols-2 lg:items-center">
            <div data-reveal>
                <p class="eyebrow">How it works</p>
                <h2 class="h-section mt-3">Booked in three taps. Confirmed on WhatsApp.</h2>
                <ol class="mt-8 space-y-6">
                    @foreach ([['Pick a date and your group', 'The calendar already knows which days are full. Children pay half on most boat trips.'], ['Send the request', 'You get a reference straight away and a WhatsApp link to fast-track it.'], ['We confirm, you show up', 'Pickup time and meeting point come by WhatsApp. Pay cash, transfer or card link.']] as [$title, $body])
                        <li class="flex gap-4">
                            <span class="grid size-9 shrink-0 place-items-center rounded-full bg-sea-600 text-sm font-bold text-white">{{ $loop->iteration }}</span>
                            <div><p class="font-bold">{{ $title }}</p><p class="mt-1 text-sm text-muted">{{ $body }}</p></div>
                        </li>
                    @endforeach
                </ol>
            </div>
            <div class="relative overflow-hidden rounded-[2rem] bg-ink p-8 text-white sm:p-10" data-reveal style="--reveal-delay: 100ms">
                <div class="absolute inset-0 glow-sea"></div><div class="absolute inset-0 glow-coral"></div>
                <div class="relative">
                    <p class="eyebrow eyebrow-light">Trip planner</p>
                    <h3 class="mt-3 font-display text-3xl font-bold">Build a whole day, get one price.</h3>
                    <p class="mt-3 text-white/70">Reef in the morning, clear kayaks after lunch, a beach massage at four. Tick the boxes, see the total in TTD or USD, send it as one request.</p>
                    <div class="mt-6 flex flex-wrap gap-2">
                        @foreach ($experiences->take(5) as $exp)<span class="chip bg-white/10 text-white ring-white/15">{{ $exp->name }}</span>@endforeach
                    </div>
                    <a href="{{ route('planner') }}" class="btn btn-sun mt-8">Start planning <x-icon name="arrow-right" class="size-4" /></a>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats --}}
    <section class="bg-sea-700 text-white">
        <div class="wrap grid grid-cols-2 gap-8 py-14 lg:grid-cols-4">
            @foreach ($site['stats'] as $stat)
                <div x-data="countUp({{ $stat['value'] }})" x-intersect.once="start()" data-reveal style="--reveal-delay: {{ $loop->index * 80 }}ms">
                    <p class="stat-num"><span x-text="value">{{ $stat['value'] }}</span>{{ $stat['suffix'] }}</p>
                    <p class="mt-1 text-sm text-white/70">{{ $stat['label'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Reviews --}}
    @if ($reviews->isNotEmpty())
        <section class="section">
            <div class="wrap">
                <div data-reveal><p class="eyebrow">Guests say</p><h2 class="h-section mt-3">Real trips, real people.</h2></div>
                <div class="mt-10 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                    @foreach ($reviews as $review)
                        <figure class="card flex flex-col" data-reveal style="--reveal-delay: {{ $loop->index * 70 }}ms">
                            <x-stars :rating="$review->rating" />
                            <blockquote class="mt-4 flex-1 text-[15px] leading-relaxed text-ink/85">&ldquo;{{ $review->body }}&rdquo;</blockquote>
                            <figcaption class="mt-5 text-sm"><span class="font-bold">{{ $review->name }}</span>@if ($review->origin)<span class="text-muted"> &middot; {{ $review->origin }}</span>@endif @if ($review->experience)<span class="mt-0.5 block text-xs text-sea-700">{{ $review->experience->name }}</span>@endif</figcaption>
                        </figure>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    {{-- Instagram --}}
    <section class="section bg-paper-deep/60">
        <div class="wrap">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between" data-reveal>
                <div><p class="eyebrow">{{ $site['social']['instagram_handle'] }}</p><h2 class="h-section mt-3">{{ $site['social']['instagram_followers'] }} people watch Tobago through us.</h2></div>
                <a href="{{ $site['social']['instagram'] }}" target="_blank" rel="noopener" class="btn btn-dark"><x-icon name="instagram" class="size-4" /> Follow along</a>
            </div>
            <div class="mt-10 grid grid-cols-2 gap-3 sm:grid-cols-4">
                @forelse ($gallery as $item)
                    <a href="{{ $item->instagram_url ?: $site['social']['instagram'] }}" target="_blank" rel="noopener" class="group relative aspect-square overflow-hidden rounded-2xl" data-reveal style="--reveal-delay: {{ $loop->index * 50 }}ms">
                        <img src="{{ $item->url() }}" alt="{{ $item->caption }}" class="h-full w-full object-cover transition duration-700 group-hover:scale-105" loading="lazy">
                    </a>
                @empty
                    @foreach ($experiences->take(8) as $exp)
                        <a href="{{ route('experiences.show', $exp) }}" class="group relative aspect-square overflow-hidden rounded-2xl" data-reveal style="--reveal-delay: {{ $loop->index * 50 }}ms">
                            <x-scene :scene="$exp->scene" :image="$exp->imageUrl()" :alt="$exp->name" class="h-full w-full transition duration-700 group-hover:scale-105" />
                            <span class="absolute right-2 bottom-2 rounded-full bg-white/90 px-2 py-1 text-[10px] font-bold text-ink">{{ $exp->categoryName() }}</span>
                        </a>
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    {{-- CTA --}}
    <section class="section">
        <div class="wrap">
            <div class="relative overflow-hidden rounded-[2.5rem] bg-ink px-6 py-14 text-center text-white sm:px-12 sm:py-20" data-reveal>
                <div class="absolute inset-0 glow-sea"></div><div class="absolute inset-0 glow-coral"></div>
                <div class="relative mx-auto max-w-2xl">
                    <h2 class="font-display text-3xl font-bold sm:text-5xl">Landing soon? Tell us your dates.</h2>
                    <p class="mt-4 text-white/70">Send your arrival dates and group and we will suggest a plan for the week, with prices, before you land.</p>
                    <div class="mt-8 flex flex-wrap justify-center gap-3">
                        <a href="{{ \App\Support\Content::whatsapp('Hi All Things Tobago! We land on ... and there are ... of us. What would you suggest?') }}" target="_blank" rel="noopener" class="btn btn-whatsapp"><x-icon name="message-circle" class="size-4" /> WhatsApp the crew</a>
                        <a href="{{ route('planner') }}" class="btn btn-onDark">Plan it yourself</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layout>
