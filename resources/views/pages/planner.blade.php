@php($site = config('site'))
<x-layout title="Plan your day in Tobago" description="Pick two or three experiences, see one price for the day in TTD or USD, and send a single booking request to All Things Tobago.">
    <section class="bg-ink text-white">
        <div class="wrap py-14 sm:py-20">
            <p class="eyebrow eyebrow-light">Trip planner</p>
            <h1 class="h-section mt-3 !text-4xl sm:!text-5xl">Build your day. We will make it run.</h1>
            <p class="mt-4 max-w-2xl text-white/70">Tick what you want, set your group, pick a date. The total updates as you go. Send it and we reply with timings and pickup.</p>
        </div>
    </section>
    <section class="section" id="plan" x-data="planner(@js(['experiences' => $experiences->map(fn ($e) => ['slug' => $e->slug, 'name' => $e->name, 'category' => $e->category, 'price' => $e->price, 'price_type' => $e->price_type, 'duration' => $e->duration, 'duration_hours' => $e->duration_hours, 'scene' => $e->scene])->values(), 'picked' => old('items', []), 'blackouts' => $blackouts, 'date' => old('date', ''), 'adults' => (int) old('adults', 2), 'children' => (int) old('children', 0)]))">
        <div class="wrap">
            <x-form-status bag="booking" key="booking_success" />
            <div class="grid gap-8 lg:grid-cols-[1fr_0.8fr] lg:items-start">
                <div>
                    <div class="flex flex-wrap gap-2">
                        <button type="button" class="chip !px-4 !py-2 !text-sm" :class="filter === 'all' && 'bg-ink text-white ring-ink'" @click="filter = 'all'">All</button>
                        @foreach ($categories as $key => $c)
                            <button type="button" class="chip !px-4 !py-2 !text-sm" :class="filter === '{{ $key }}' && 'bg-ink text-white ring-ink'" @click="filter = '{{ $key }}'"><x-icon :name="$c['icon']" class="size-3.5" /> {{ $c['name'] }}</button>
                        @endforeach
                    </div>
                    <div class="mt-6 grid gap-3 sm:grid-cols-2">
                        @foreach ($experiences as $exp)
                            <button type="button" class="group flex gap-4 rounded-2xl bg-white p-3 text-left ring-1 ring-line transition hover:ring-sea-500" :class="has('{{ $exp->slug }}') && '!ring-2 !ring-sea-600 bg-sea-50'" x-show="filter === 'all' || filter === '{{ $exp->category }}'" @click="toggle('{{ $exp->slug }}')" :aria-pressed="has('{{ $exp->slug }}')">
                                <span class="relative size-20 shrink-0 overflow-hidden rounded-xl"><x-scene :scene="$exp->scene" :image="$exp->imageUrl()" :alt="$exp->name" /></span>
                                <span class="min-w-0 flex-1">
                                    <span class="block text-sm font-bold leading-tight">{{ $exp->name }}</span>
                                    <span class="mt-1 block text-xs text-muted">{{ $exp->duration }} &middot; from <x-price :amount="$exp->price" /> {{ $exp->priceLabel() }}</span>
                                </span>
                                <span class="grid size-7 shrink-0 place-items-center self-center rounded-full ring-1 ring-line transition" :class="has('{{ $exp->slug }}') ? 'bg-sea-600 text-white ring-sea-600' : 'bg-white text-transparent'"><x-icon name="check" class="size-4" /></span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <form method="post" action="{{ route('planner.store') }}" class="card space-y-5 !p-6 lg:sticky lg:top-28">
                    @csrf
                    <div class="flex items-start justify-between gap-4">
                        <div><p class="text-[11px] font-bold tracking-[0.16em] text-muted uppercase">Your day</p><h2 class="h-card mt-1"><span x-text="items.length">0</span> <span x-text="items.length === 1 ? 'experience' : 'experiences'">experiences</span> &middot; <span x-text="hours">0</span>h</h2></div>
                        <p class="text-right"><span class="block text-[10px] font-bold tracking-wider text-muted uppercase">Total</span><span class="font-display text-2xl font-bold tabular-nums" x-text="fmt(total)">TT$0</span></p>
                    </div>
                    <ul class="divide-y divide-line rounded-2xl bg-paper ring-1 ring-line" x-show="items.length" x-cloak>
                        <template x-for="e in items" :key="e.slug">
                            <li class="flex items-center gap-3 px-4 py-3 text-sm">
                                <span class="min-w-0 flex-1 truncate font-semibold" x-text="e.name"></span>
                                <span class="tabular-nums text-muted" x-text="fmt(subtotal(e))"></span>
                                <button type="button" class="grid size-7 place-items-center rounded-full text-muted hover:bg-white hover:text-rose-600" @click="toggle(e.slug)" aria-label="Remove"><x-icon name="x" class="size-3.5" /></button>
                                <input type="hidden" name="items[]" :value="e.slug">
                            </li>
                        </template>
                    </ul>
                    <p class="rounded-2xl bg-paper px-4 py-3 text-sm text-muted" x-show="! items.length">Pick experiences on the left to start.</p>
                    <p class="hint !mt-1" x-show="hours > 9" x-cloak><x-icon name="info" class="inline size-3.5" /> That is a long day. We may suggest splitting it over two.</p>
                    @error('items', 'booking')<p class="field-error">{{ $message }}</p>@enderror
                    <div class="space-y-2">
                        <x-stepper model="adults" label="Adults" :floor="1" />
                        <x-stepper model="children" label="Children" hint="Half price on per-person trips" />
                    </div>
                    <div>
                        <p class="label flex items-center justify-between">Date <span class="text-xs font-semibold text-sea-700" x-text="dateLabel"></span></p>
                        <x-calendar />
                        <noscript><input type="date" name="date" class="input mt-2" min="{{ today()->toDateString() }}" required></noscript>
                        <input type="hidden" name="date" :value="date">
                        @error('date', 'booking')<p class="field-error">{{ $message }}</p>@enderror
                        <input type="hidden" name="time_slot" value="morning">
                    </div>
                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-field name="name" label="Your name" bag="booking" required autocomplete="name" />
                        <x-field name="phone" label="WhatsApp number" type="tel" bag="booking" required autocomplete="tel" placeholder="+1 868 000 0000" />
                        <x-field name="email" label="Email" type="email" bag="booking" autocomplete="email" class="sm:col-span-2" />
                        <x-field name="pickup" label="Pickup area" type="select" bag="booking" :options="array_combine($site['pickup_areas'], $site['pickup_areas'])" placeholder="Choose" class="sm:col-span-2" />
                    </div>
                    <x-field name="notes" label="Notes" type="textarea" bag="booking" placeholder="Occasion, timings you prefer, anyone who does not swim..." />
                    <x-consent bag="booking" />
                    <button class="btn btn-coral w-full !py-3.5" :disabled="! items.length">Send my plan <x-icon name="arrow-right" class="size-4" /></button>
                </form>
            </div>
        </div>
    </section>
</x-layout>
