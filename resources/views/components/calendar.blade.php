{{-- Month picker; expects the Alpine booking/planner component in scope and a hidden "date" input bound elsewhere. --}}
<div class="rounded-2xl bg-paper p-3 ring-1 ring-line" x-cloak>
    <div class="flex items-center justify-between px-1">
        <button type="button" class="grid size-9 place-items-center rounded-full hover:bg-white disabled:opacity-30" @click="prevMonth()" :disabled="! canPrev" aria-label="Previous month"><x-icon name="chevron-left" class="size-4" /></button>
        <p class="text-sm font-bold" x-text="monthLabel"></p>
        <button type="button" class="grid size-9 place-items-center rounded-full hover:bg-white" @click="nextMonth()" aria-label="Next month"><x-icon name="chevron-right" class="size-4" /></button>
    </div>
    <div class="mt-2 grid grid-cols-7 gap-1 text-center text-[10px] font-bold tracking-wider text-muted uppercase">
        @foreach (['Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa', 'Su'] as $d)<span>{{ $d }}</span>@endforeach
    </div>
    <div class="mt-1 grid grid-cols-7 gap-1">
        <template x-for="(cell, i) in cells" :key="i">
            <button type="button" class="cal-day" :data-state="cell ? cell.state : 'blank'" :disabled="! cell || cell.state === 'past' || cell.state === 'full'" @click="pick(cell)" x-text="cell ? cell.d : ''" :aria-pressed="cell && cell.state === 'selected'"></button>
        </template>
    </div>
    <p class="mt-2 px-1 text-[11px] text-muted"><span class="inline-block size-2 rounded-sm bg-rose-200 align-middle"></span> Fully booked</p>
</div>
