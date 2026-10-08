<x-admin title="Calendar">
    <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div><h1 class="font-display text-3xl font-bold">{{ $month->format('F Y') }}</h1><p class="mt-1 text-sm text-muted">Open bookings by day. Block a date to stop new requests for it.</p></div>
        <div class="flex gap-2"><a href="{{ route('admin.calendar', ['month' => $month->copy()->subMonth()->format('Y-m')]) }}" class="btn btn-light !px-3"><x-icon name="chevron-left" class="size-4" /></a><a href="{{ route('admin.calendar') }}" class="btn btn-light">Today</a><a href="{{ route('admin.calendar', ['month' => $month->copy()->addMonth()->format('Y-m')]) }}" class="btn btn-light !px-3"><x-icon name="chevron-right" class="size-4" /></a></div>
    </div>
    <div class="mt-6 grid gap-6 *:min-w-0 xl:grid-cols-[1fr_18rem]">
        <div class="overflow-x-auto rounded-3xl bg-white p-3 ring-1 ring-line/70">
            <div class="grid min-w-[40rem] grid-cols-7 gap-1 text-center text-[10px] font-bold tracking-wider text-muted uppercase">@foreach (['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $d)<span class="py-1">{{ $d }}</span>@endforeach</div>
            <div class="mt-1 grid min-w-[40rem] grid-cols-7 gap-1">
                @foreach ($days as $day)
                    <div class="min-h-24 rounded-xl p-1.5 {{ $day['inMonth'] ? 'bg-paper' : 'bg-paper/40 opacity-50' }} {{ $day['date']->isToday() ? 'ring-2 ring-sea-600' : 'ring-1 ring-line/60' }} {{ $day['blackout'] ? '!bg-rose-50' : '' }}">
                        <div class="flex items-center justify-between"><span class="text-xs font-bold {{ $day['date']->isToday() ? 'text-sea-700' : '' }}">{{ $day['date']->day }}</span>@if ($day['blackout'])<span class="text-[10px] font-bold text-rose-700" title="{{ $day['blackout']->reason }}">Blocked</span>@endif</div>
                        <ul class="mt-1 space-y-1">@foreach ($day['bookings']->take(3) as $b)<li><a href="{{ route('admin.bookings.show', $b) }}" class="block truncate rounded-md px-1.5 py-0.5 text-[11px] font-semibold tone-{{ $b->statusTone() }} ring-1">{{ $b->name }} &middot; {{ Str::limit($b->title(), 14, '') }}</a></li>@endforeach @if ($day['bookings']->count() > 3)<li class="px-1.5 text-[10px] text-muted">+{{ $day['bookings']->count() - 3 }} more</li>@endif</ul>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="space-y-6">
            <form method="post" action="{{ route('admin.blackouts.store') }}" class="rounded-3xl bg-white p-5 ring-1 ring-line/70 space-y-3">@csrf<h2 class="font-display text-lg font-bold">Block a date</h2><x-field name="date" label="Date" type="date" required /><x-field name="reason" label="Reason" placeholder="Boat maintenance, day off..." /><button class="btn btn-dark w-full">Block</button></form>
            <div class="rounded-3xl bg-white p-5 ring-1 ring-line/70"><h2 class="font-display text-lg font-bold">Blocked dates</h2><ul class="mt-3 space-y-2 text-sm">@forelse ($blackouts as $bl)<li class="flex items-center justify-between gap-2"><span><strong>{{ $bl->date->format('D j M') }}</strong> <span class="text-muted">{{ $bl->reason }}</span></span><form method="post" action="{{ route('admin.blackouts.destroy', $bl) }}">@csrf @method('DELETE')<button class="text-rose-600 hover:underline" aria-label="Unblock"><x-icon name="x" class="size-4" /></button></form></li>@empty<li class="text-muted">None.</li>@endforelse</ul></div>
        </div>
    </div>
</x-admin>
