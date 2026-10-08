@props(['rating' => 5, 'size' => 'size-4'])
<span {{ $attributes->merge(['class' => 'inline-flex items-center gap-0.5 text-sun-500']) }} aria-label="{{ $rating }} out of 5">
    @for ($i = 1; $i <= 5; $i++)
        <x-icon name="star" class="{{ $size }} {{ $i <= round($rating) ? 'fill-current' : 'opacity-25' }}" />
    @endfor
</span>
