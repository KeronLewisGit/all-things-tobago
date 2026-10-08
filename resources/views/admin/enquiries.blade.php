<x-admin title="Enquiries">
    <h1 class="font-display text-3xl font-bold">Enquiries</h1><p class="mt-1 text-sm text-muted">Accommodation, tickets and general messages.</p>
    <div class="mt-6 space-y-3">
        @forelse ($enquiries as $e)
            <div class="rounded-3xl bg-white p-5 ring-1 ring-line/70">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div><p class="font-bold">{{ $e->name }} <span class="chip ml-1 !py-0 !text-[10px]">{{ $e->typeLabel() }}</span></p><p class="mt-0.5 text-xs text-muted">{{ $e->reference }} &middot; {{ $e->created_at->diffForHumans() }} &middot; {{ $e->phone }} {{ $e->email }}</p><p class="mt-1 text-xs text-muted">{{ collect([$e->from ? $e->from->format('j M') : null, $e->to ? 'to '.$e->to->format('j M Y') : null, $e->guests ? $e->guests.' guests' : null, $e->budget])->filter()->implode(' · ') }}</p></div>
                    <form method="post" action="{{ route('admin.enquiries.update', $e) }}" class="flex items-center gap-2">@csrf @method('PATCH')<select name="status" class="input !w-auto !py-1.5 !text-xs">@foreach (['new' => 'New', 'replied' => 'Replied', 'booked' => 'Booked', 'closed' => 'Closed'] as $k => $l)<option value="{{ $k }}" @selected($e->status === $k)>{{ $l }}</option>@endforeach</select><button class="btn btn-light !px-3 !py-1.5 !text-xs">Save</button>@if ($e->phone)<a href="https://wa.me/{{ preg_replace('/\D+/', '', $e->phone) }}" target="_blank" class="btn btn-whatsapp !px-3 !py-1.5 !text-xs">WhatsApp</a>@endif</form>
                </div>
                @if ($e->message)<p class="mt-3 text-sm">{{ $e->message }}</p>@endif
            </div>
        @empty<p class="text-muted">No enquiries yet.</p>@endforelse
    </div>
    <div class="mt-4">{{ $enquiries->links() }}</div>
</x-admin>
