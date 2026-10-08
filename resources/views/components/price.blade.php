@props(['amount', 'label' => null])
{{-- Shows TTD or USD depending on the visitor's toggle; TTD is rendered server-side so no-JS visitors see a price. --}}
<span {{ $attributes->merge(['class' => 'inline-flex items-baseline gap-1']) }}>
    <span class="font-display font-bold tabular-nums" x-data x-text="$store.currency.format({{ (int) $amount }})">{{ \App\Support\Content::ttd($amount) }}</span>
    @if ($label)<span class="text-xs font-semibold text-muted">{{ $label }}</span>@endif
</span>
