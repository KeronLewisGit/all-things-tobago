<x-admin :title="$booking->reference">
    <a href="{{ route('admin.bookings') }}" class="link-arrow text-xs"><x-icon name="chevron-left" class="size-3.5" /> Back to bookings</a>
    <div class="mt-3 flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
        <div><div class="flex flex-wrap items-center gap-2"><h1 class="font-display text-3xl font-bold">{{ $booking->name }}</h1><x-status-badge :booking="$booking" /></div><p class="mt-1 text-sm text-muted">{{ $booking->reference }} &middot; {{ $booking->title() }} &middot; received {{ $booking->created_at->format('D j M, g:ia') }} ({{ $booking->created_at->diffForHumans() }})</p></div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ $whatsapp }}" target="_blank" rel="noopener" class="btn btn-whatsapp"><x-icon name="message-circle" class="size-4" /> WhatsApp</a>
            <a href="tel:{{ $booking->phone }}" class="btn btn-light"><x-icon name="phone" class="size-4" /> Call</a>
            @if ($booking->email)<a href="mailto:{{ $booking->email }}?subject={{ rawurlencode('Your All Things Tobago booking '.$booking->reference) }}" class="btn btn-light"><x-icon name="mail" class="size-4" /> Email</a>@endif
        </div>
    </div>
    <div class="mt-6 grid gap-6 *:min-w-0 xl:grid-cols-[1.2fr_0.8fr]">
        <div class="space-y-6">
            <form method="post" action="{{ route('admin.bookings.update', $booking) }}" class="rounded-3xl bg-white p-6 ring-1 ring-line/70">
                @csrf @method('PATCH')
                <p class="text-xs font-bold tracking-wider text-muted uppercase">Move to stage</p>
                <div class="mt-3 flex flex-wrap gap-2">
                    @foreach (\App\Models\Booking::STATUSES as $key => $s)<button name="status" value="{{ $key }}" class="btn !px-4 !py-2 !text-xs {{ $booking->status === $key ? 'btn-dark' : 'btn-light' }}">@if ($booking->status === $key)<x-icon name="check" class="size-3.5" />@endif {{ $s['label'] }}</button>@endforeach
                </div>
            </form>
            <div class="rounded-3xl bg-white p-6 ring-1 ring-line/70">
                <h2 class="font-display text-xl font-bold">The request</h2>
                <dl class="mt-4 grid gap-x-6 gap-y-3 text-sm sm:grid-cols-2">
                    <div><dt class="text-xs font-bold tracking-wider text-muted uppercase">Date</dt><dd class="font-semibold">{{ $booking->date->format('l j F Y') }}@if ($booking->slotLabel())<span class="block text-xs font-normal text-muted">{{ $booking->slotLabel() }}</span>@endif</dd></div>
                    <div><dt class="text-xs font-bold tracking-wider text-muted uppercase">Guests</dt><dd class="font-semibold">{{ $booking->adults }} adults, {{ $booking->children }} children</dd></div>
                    <div><dt class="text-xs font-bold tracking-wider text-muted uppercase">Phone</dt><dd class="font-semibold">{{ $booking->phone }} <span class="text-xs font-normal text-muted">(prefers {{ $booking->contact_via }})</span></dd></div>
                    <div><dt class="text-xs font-bold tracking-wider text-muted uppercase">Email</dt><dd class="font-semibold break-all">{{ $booking->email ?? '—' }}</dd></div>
                    <div><dt class="text-xs font-bold tracking-wider text-muted uppercase">Pickup</dt><dd class="font-semibold">{{ $booking->pickup ?? '—' }}</dd></div>
                    <div><dt class="text-xs font-bold tracking-wider text-muted uppercase">Source</dt><dd class="font-semibold capitalize">{{ $booking->source['utm_source'] ?? ($booking->source['referrer_host'] ?? 'Direct') }} &middot; {{ $booking->source['device'] ?? '' }}</dd></div>
                </dl>
                @if ($booking->type === 'planner')
                    <h3 class="mt-6 text-xs font-bold tracking-wider text-muted uppercase">Planned day</h3>
                    <ul class="mt-2 divide-y divide-line rounded-2xl bg-paper ring-1 ring-line">@foreach ($booking->items as $item)<li class="flex justify-between px-4 py-2.5 text-sm"><span class="font-semibold">{{ $item['name'] }}</span><span class="tabular-nums text-muted">TT${{ number_format($item['subtotal']) }}</span></li>@endforeach</ul>
                @elseif ($booking->experience)
                    <p class="mt-6 text-sm"><a href="{{ route('experiences.show', $booking->experience) }}" target="_blank" class="link">{{ $booking->experience->name }}</a> &middot; {{ $booking->experience->duration }} &middot; from TT${{ number_format($booking->experience->price) }} {{ $booking->experience->priceLabel() }}</p>
                @endif
                @if ($booking->notes)<blockquote class="mt-5 rounded-2xl bg-sun-100 p-4 text-sm">&ldquo;{{ $booking->notes }}&rdquo;</blockquote>@endif
            </div>
            <form method="post" action="{{ route('admin.bookings.update', $booking) }}" class="rounded-3xl bg-white p-6 ring-1 ring-line/70">
                @csrf @method('PATCH')
                <h2 class="font-display text-xl font-bold">Price</h2>
                <p class="mt-1 text-sm text-muted">Website estimate was {{ $ttd }}. Adjust after confirming with the guest.</p>
                <div class="mt-3 flex gap-2"><div class="relative flex-1"><span class="absolute top-1/2 left-3.5 -translate-y-1/2 text-sm font-bold text-muted">TT$</span><input type="number" name="estimate" value="{{ $booking->estimate }}" class="input !pl-12" min="0" step="5"></div><button class="btn btn-dark">Save price</button></div>
            </form>
        </div>
        <div class="rounded-3xl bg-white p-6 ring-1 ring-line/70">
            <h2 class="font-display text-xl font-bold">Activity</h2>
            <form method="post" action="{{ route('admin.bookings.update', $booking) }}" class="mt-4 space-y-3">
                @csrf @method('PATCH')
                <div class="flex flex-wrap gap-2">@foreach (['note' => 'Note', 'whatsapp' => 'WhatsApp', 'call' => 'Call', 'email' => 'Email'] as $key => $label)<label class="option"><input type="radio" name="note_type" value="{{ $key }}" class="peer sr-only" {{ $key === 'note' ? 'checked' : '' }}><span class="!px-3 !py-1.5 !text-xs">{{ $label }}</span></label>@endforeach</div>
                <textarea name="note" rows="3" class="input" placeholder="What happened?" required></textarea>
                <button class="btn btn-primary w-full">Add to log</button>
            </form>
            <ol class="mt-6 space-y-4 border-l-2 border-line pl-4 text-sm">
                @foreach ($booking->activities as $note)<li><p class="text-xs text-muted">{{ $note->created_at->format('D j M, g:ia') }} &middot; {{ $note->user?->name ?? 'Website' }} &middot; {{ ucfirst($note->type) }}</p><p class="mt-0.5">{{ $note->body }}</p></li>@endforeach
            </ol>
            <form method="post" action="{{ route('admin.bookings.destroy', $booking) }}" class="mt-8 border-t border-line pt-4" onsubmit="return confirm('Delete this booking permanently?')">@csrf @method('DELETE')<button class="text-xs font-semibold text-rose-700 hover:underline">Delete booking</button></form>
        </div>
    </div>
</x-admin>
