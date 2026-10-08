@props(['light' => false, 'compact' => false])
<span {{ $attributes->merge(['class' => 'flex items-center gap-2.5']) }}>
    <span class="relative grid size-10 shrink-0 place-items-center overflow-hidden rounded-xl bg-sea-600 text-white shadow-sm ring-2 ring-sun-400/80">
        <x-icon name="palmtree" class="size-5" />
        <span class="absolute inset-x-0 bottom-0 h-2 bg-sun-400/90"></span>
    </span>
    @unless ($compact)
        <span class="leading-tight">
            <span class="block font-display text-lg font-bold tracking-tight {{ $light ? 'text-white' : 'text-ink' }}">All Things Tobago</span>
            <span class="block text-[10px] font-bold tracking-[0.18em] uppercase max-[359px]:hidden {{ $light ? 'text-sun-300' : 'text-sea-700' }}">Tours · Boats · Beaches</span>
        </span>
    @endunless
</span>
