<x-admin title="Reviews">
    <h1 class="font-display text-3xl font-bold">Reviews</h1><p class="mt-1 text-sm text-muted">Approve what shows on the site. Featured reviews appear on the home page.</p>
    <div class="mt-6 grid gap-6 *:min-w-0 xl:grid-cols-[1fr_22rem]">
        <div class="space-y-3">
            @forelse ($reviews as $r)
                <div class="rounded-3xl bg-white p-5 ring-1 ring-line/70">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div><x-stars :rating="$r->rating" /><p class="mt-1 font-bold">{{ $r->name }} <span class="font-normal text-muted">{{ $r->origin ? '· '.$r->origin : '' }} {{ $r->experience ? '· '.$r->experience->name : '' }}</span></p></div>
                        <form method="post" action="{{ route('admin.reviews.update', $r) }}" class="flex items-center gap-3 text-sm">@csrf @method('PATCH')<label class="flex items-center gap-1.5"><input type="checkbox" name="approved" value="1" class="size-4 rounded border-line" @checked($r->approved)> Approved</label><label class="flex items-center gap-1.5"><input type="checkbox" name="featured" value="1" class="size-4 rounded border-line" @checked($r->featured)> Featured</label><button class="btn btn-light !px-3 !py-1.5 !text-xs">Save</button></form>
                    </div>
                    <p class="mt-3 text-sm">{{ $r->body }}</p>
                    <form method="post" action="{{ route('admin.reviews.destroy', $r) }}" class="mt-3" onsubmit="return confirm('Delete this review?')">@csrf @method('DELETE')<button class="text-xs font-semibold text-rose-700 hover:underline">Delete</button></form>
                </div>
            @empty<p class="text-muted">No reviews yet.</p>@endforelse
        </div>
        <form method="post" action="{{ route('admin.reviews.store') }}" class="space-y-3 self-start rounded-3xl bg-white p-5 ring-1 ring-line/70">@csrf<h2 class="font-display text-lg font-bold">Add a review</h2><p class="text-xs text-muted">Paste reviews guests send on WhatsApp or Instagram.</p><x-field name="name" label="Guest name" required /><x-field name="origin" label="From (city or country)" /><x-field name="experience_id" label="Experience" type="select" :options="$experiences->pluck('name', 'id')->all()" placeholder="General" /><x-field name="rating" label="Rating" type="select" :options="[5 => '5 stars', 4 => '4 stars', 3 => '3 stars']" :value="5" /><x-field name="body" label="What they said" type="textarea" required /><button class="btn btn-primary w-full">Add review</button></form>
    </div>
</x-admin>
