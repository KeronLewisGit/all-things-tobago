<x-admin title="Dashboard">
    @php($max = max(1, $days->max('count')))
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div><p class="text-[11px] font-bold tracking-[0.16em] text-sea-700 uppercase">{{ now()->format('l j F Y') }}</p><h1 class="mt-1 font-display text-3xl font-bold">Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}.</h1><p class="mt-1 text-sm text-muted"><strong class="text-ink">{{ $newCount }} new {{ Str::plural('request', $newCount) }}</strong> waiting for a reply, {{ $todayCount }} {{ Str::plural('trip', $todayCount) }} today.</p></div>
        <a href="{{ route('admin.bookings', ['status' => 'new']) }}" class="btn btn-primary">Work the new requests <x-icon name="arrow-right" class="size-4" /></a>
    </div>
    <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([['Requests this month', $monthRequests, 'inbox', ''], ['Trips today', $todayCount, 'calendar-days', ''], ['Pipeline value', 'TT$'.number_format($pipelineRevenue), 'receipt', 'open bookings from today'], ['Revenue this month', 'TT$'.number_format($monthRevenue), 'badge-check', 'paid and completed']] as [$label, $value, $icon, $sub])
            <div class="rounded-3xl bg-white p-5 ring-1 ring-line/70"><div class="flex items-center justify-between text-muted"><p class="text-xs font-semibold">{{ $label }}</p><x-icon :name="$icon" class="size-4" /></div><p class="mt-2 font-display text-3xl font-bold tabular-nums">{{ $value }}</p>@if ($sub)<p class="mt-1 text-xs text-muted">{{ $sub }}</p>@endif</div>
        @endforeach
    </div>
    <div class="mt-6 grid gap-6 *:min-w-0 xl:grid-cols-[1.4fr_1fr]">
        <div class="rounded-3xl bg-white p-6 ring-1 ring-line/70">
            <div class="flex items-center justify-between"><h2 class="font-display text-xl font-bold">Requests per day</h2><span class="text-xs text-muted">Last 30 days</span></div>
            <div class="mt-5 flex h-36 items-end gap-1">
                @foreach ($days as $day)<div class="flex-1 rounded-t bg-sea-600 transition hover:bg-coral-500" style="height: {{ max(3, $day['count'] / $max * 100) }}%" title="{{ $day['date'] }}: {{ $day['count'] }}"></div>@endforeach
            </div>
            <div class="mt-1 flex justify-between text-[10px] text-muted"><span>{{ $days->first()['date'] }}</span><span>Today</span></div>
            <h3 class="mt-8 font-display text-lg font-bold">Upcoming trips</h3>
            <div class="mt-3 overflow-x-auto">
                <table class="table-clean"><thead><tr><th>Date</th><th>Guest</th><th>Experience</th><th>Guests</th><th>Status</th></tr></thead><tbody>
                    @forelse ($upcoming as $b)<tr><td class="whitespace-nowrap font-semibold">{{ $b->date->format('D j M') }}<span class="block text-xs font-normal text-muted">{{ $b->slotLabel() }}</span></td><td><a href="{{ route('admin.bookings.show', $b) }}" class="font-semibold hover:text-sea-700">{{ $b->name }}</a><span class="block text-xs text-muted">{{ $b->reference }}</span></td><td>{{ $b->title() }}</td><td>{{ $b->guests() }}</td><td><x-status-badge :booking="$b" /></td></tr>
                    @empty<tr><td colspan="5" class="text-muted">Nothing booked ahead yet.</td></tr>@endforelse
                </tbody></table>
            </div>
        </div>
        <div class="space-y-6">
            <div class="rounded-3xl bg-white p-6 ring-1 ring-line/70">
                <h2 class="font-display text-xl font-bold">Most requested</h2><p class="text-xs text-muted">Last 60 days</p>
                @php($top = max(1, $topExperiences->max('bookings_count')))
                <ul class="mt-4 space-y-3">@foreach ($topExperiences as $e)<li><div class="flex justify-between text-sm"><span class="truncate font-semibold">{{ $e->name }}</span><span class="text-muted">{{ $e->bookings_count }}</span></div><div class="mt-1.5 h-2 overflow-hidden rounded-full bg-paper-deep"><div class="h-full rounded-full bg-sun-400" style="width: {{ $e->bookings_count / $top * 100 }}%"></div></div></li>@endforeach</ul>
            </div>
            <div class="rounded-3xl bg-white p-6 ring-1 ring-line/70">
                <h2 class="font-display text-xl font-bold">Where requests come from</h2>
                <ul class="mt-4 space-y-2 text-sm">@foreach ($sources as $src => $n)<li class="flex justify-between"><span class="capitalize">{{ $src }}</span><span class="font-bold">{{ $n }}</span></li>@endforeach</ul>
                <div class="mt-5 grid grid-cols-2 gap-3 text-sm">
                    <a href="{{ route('admin.reviews') }}" class="rounded-2xl bg-paper p-3 ring-1 ring-line hover:ring-sea-500"><span class="block font-display text-2xl font-bold">{{ $pendingReviews }}</span>reviews to approve</a>
                    <a href="{{ route('admin.enquiries') }}" class="rounded-2xl bg-paper p-3 ring-1 ring-line hover:ring-sea-500"><span class="block font-display text-2xl font-bold">{{ $newEnquiries }}</span>new enquiries</a>
                </div>
            </div>
        </div>
    </div>
</x-admin>
