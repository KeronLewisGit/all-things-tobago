<x-admin title="Bookings">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div><h1 class="font-display text-3xl font-bold">Bookings</h1><p class="mt-1 text-sm text-muted">{{ $bookings->total() }} match. Click a name to open the record.</p></div>
        <a href="{{ route('admin.bookings.export') }}" class="btn btn-light"><x-icon name="download" class="size-4" /> Export CSV</a>
    </div>
    <div class="mt-6 flex flex-wrap gap-2">
        @foreach (['' => 'All', 'new' => 'New', 'open' => 'Open', 'confirmed' => 'Confirmed', 'paid' => 'Paid', 'completed' => 'Completed', 'cancelled' => 'Cancelled'] as $key => $label)
            <a href="{{ route('admin.bookings', array_filter(['status' => $key, 'q' => request('q'), 'when' => request('when')])) }}" class="chip !px-3.5 !py-1.5 {{ request('status', '') === $key ? 'bg-ink text-white ring-ink' : '' }}">{{ $label }} <span class="opacity-60">{{ $counts[$key ?: 'all'] ?? 0 }}</span></a>
        @endforeach
        <a href="{{ route('admin.bookings', array_filter(['when' => request('when') === 'upcoming' ? null : 'upcoming', 'status' => request('status')])) }}" class="chip !px-3.5 !py-1.5 {{ request('when') === 'upcoming' ? 'bg-sea-600 text-white ring-sea-600' : '' }}"><x-icon name="calendar" class="size-3.5" /> Upcoming first</a>
    </div>
    <form class="mt-4 flex gap-2"><input type="hidden" name="status" value="{{ request('status') }}"><input type="search" name="q" value="{{ request('q') }}" placeholder="Search name, phone, reference" class="input max-w-sm"><button class="btn btn-dark">Search</button></form>
    <div class="mt-4 overflow-x-auto rounded-3xl bg-white ring-1 ring-line/70">
        <table class="table-clean"><thead><tr><th>Guest</th><th>Experience</th><th>Date</th><th>Guests</th><th>Estimate</th><th>Status</th><th>Received</th><th></th></tr></thead><tbody>
            @forelse ($bookings as $b)
                <tr class="hover:bg-paper/60">
                    <td><a href="{{ route('admin.bookings.show', $b) }}" class="font-bold hover:text-sea-700">{{ $b->name }}</a><span class="block text-xs text-muted">{{ $b->reference }} &middot; {{ $b->phone }}</span></td>
                    <td class="min-w-48">{{ $b->title() }}<span class="block text-xs text-muted">{{ $b->pickup }}</span></td>
                    <td class="whitespace-nowrap font-semibold">{{ $b->date->format('D j M') }}<span class="block text-xs font-normal text-muted">{{ $b->slotLabel() }}</span></td>
                    <td>{{ $b->adults }}A {{ $b->children ? '+ '.$b->children.'C' : '' }}</td>
                    <td class="tabular-nums">TT${{ number_format($b->estimate) }}</td>
                    <td><x-status-badge :booking="$b" /></td>
                    <td class="whitespace-nowrap text-xs text-muted">{{ $b->created_at->diffForHumans(short: true) }}</td>
                    <td><a href="{{ route('admin.bookings.show', $b) }}" class="btn btn-light !px-3 !py-1.5 !text-xs">Open</a></td>
                </tr>
            @empty<tr><td colspan="8" class="text-muted">No bookings match.</td></tr>@endforelse
        </tbody></table>
    </div>
    <div class="mt-4">{{ $bookings->links() }}</div>
</x-admin>
