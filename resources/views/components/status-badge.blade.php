@props(['booking'])
<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-bold whitespace-nowrap ring-1 tone-'.$booking->statusTone()]) }}>
    <span class="size-1.5 rounded-full bg-current"></span>{{ $booking->statusLabel() }}
</span>
