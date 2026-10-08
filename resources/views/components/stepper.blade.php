@props(['model', 'label', 'hint' => null, 'floor' => 0])
<div class="flex items-center justify-between gap-3 rounded-2xl bg-white px-4 py-3 ring-1 ring-line">
    <div><p class="text-sm font-bold">{{ $label }}</p>@if ($hint)<p class="text-xs text-muted">{{ $hint }}</p>@endif</div>
    <div class="stepper">
        <button type="button" @click="dec('{{ $model }}', {{ $floor }})" :disabled="{{ $model }} <= {{ $floor }}" aria-label="Fewer {{ strtolower($label) }}"><x-icon name="minus" class="size-4" /></button>
        <output x-text="{{ $model }}"></output>
        <button type="button" @click="inc('{{ $model }}')" aria-label="More {{ strtolower($label) }}"><x-icon name="plus" class="size-4" /></button>
    </div>
    <input type="hidden" name="{{ $model }}" :value="{{ $model }}">
</div>
