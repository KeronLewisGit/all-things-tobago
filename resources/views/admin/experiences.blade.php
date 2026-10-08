<x-admin title="Experiences">
    <h1 class="font-display text-3xl font-bold">Experiences</h1><p class="mt-1 text-sm text-muted">Prices, visibility and photos. Hidden experiences stay in the system but leave the site.</p>
    <div class="mt-6 overflow-x-auto rounded-3xl bg-white ring-1 ring-line/70">
        <table class="table-clean"><thead><tr><th>Experience</th><th>Category</th><th>Price</th><th>Guests</th><th>Requests</th><th>Shown</th><th></th></tr></thead><tbody>
            @foreach ($experiences as $e)<tr class="hover:bg-paper/60"><td class="flex items-center gap-3"><span class="size-12 shrink-0 overflow-hidden rounded-lg"><x-scene :scene="$e->scene" :image="$e->imageUrl()" :alt="$e->name" /></span><span><a href="{{ route('admin.experiences.edit', $e) }}" class="font-bold hover:text-sea-700">{{ $e->name }}</a>@if ($e->featured)<span class="ml-1 chip !py-0 !text-[10px]">Popular</span>@endif<span class="block text-xs text-muted">{{ $e->duration }}</span></span></td><td>{{ $categories[$e->category]['name'] ?? $e->category }}</td><td class="whitespace-nowrap tabular-nums">TT${{ number_format($e->price) }} <span class="text-xs text-muted">{{ $e->priceLabel() }}</span></td><td>{{ $e->min_guests }}–{{ $e->max_guests }}</td><td>{{ $e->bookings_count }}</td><td>{!! $e->active ? '<span class="chip tone-emerald">Live</span>' : '<span class="chip tone-slate">Hidden</span>' !!}</td><td><a href="{{ route('admin.experiences.edit', $e) }}" class="btn btn-light !px-3 !py-1.5 !text-xs">Edit</a></td></tr>@endforeach
        </tbody></table>
    </div>
</x-admin>
