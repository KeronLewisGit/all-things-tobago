@props(['experience', 'delay' => 0])
<a href="{{ route('experiences.show', $experience) }}" {{ $attributes->merge(['class' => 'group flex flex-col overflow-hidden rounded-3xl bg-white shadow-card ring-1 ring-line/70 transition hover:-translate-y-1 hover:shadow-lift']) }} data-reveal style="--reveal-delay: {{ $delay }}ms">
    <div class="relative aspect-[4/3] overflow-hidden">
        <x-scene :scene="$experience->scene" :image="$experience->imageUrl()" :alt="$experience->name" class="h-full w-full transition duration-700 group-hover:scale-105" />
        <div class="absolute top-3 left-3 flex gap-1.5">
            <span class="chip bg-white/90 backdrop-blur">{{ $experience->categoryName() }}</span>
            @if ($experience->featured)<span class="chip bg-sun-400/95 ring-sun-500/40"><x-icon name="flame" class="size-3" /> Popular</span>@endif
        </div>
        <div class="absolute right-3 bottom-3 rounded-full bg-ink/80 px-3 py-1.5 text-sm text-white backdrop-blur">
            <span class="text-[10px] font-bold tracking-wider text-white/70 uppercase">from</span> <x-price :amount="$experience->price" class="text-white" />
        </div>
    </div>
    <div class="flex flex-1 flex-col p-5">
        <h3 class="h-card group-hover:text-sea-700">{{ $experience->name }}</h3>
        <p class="mt-1.5 text-sm text-muted">{{ $experience->tagline }}</p>
        <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-1 text-xs font-semibold text-muted">
            <span class="inline-flex items-center gap-1"><x-icon name="clock" class="size-3.5" /> {{ $experience->duration }}</span>
            <span class="inline-flex items-center gap-1"><x-icon name="users" class="size-3.5" /> Up to {{ $experience->max_guests }}</span>
            @if (isset($experience->reviews_avg_rating) && $experience->reviews_avg_rating)
                <span class="inline-flex items-center gap-1 text-ink"><x-icon name="star" class="size-3.5 fill-sun-500 text-sun-500" /> {{ number_format($experience->reviews_avg_rating, 1) }} ({{ $experience->reviews_count }})</span>
            @endif
        </div>
        <span class="link-arrow mt-4">See details and book <x-icon name="arrow-right" class="size-4" /></span>
    </div>
</a>
