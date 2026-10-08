@php($cat = $category ? $categories[$category] : null)
<x-layout :title="$cat ? $cat['name'].' in Tobago' : 'Tours and experiences in Tobago'" :description="$cat ? $cat['blurb'].' Book with All Things Tobago.' : 'Every tour, boat trip and water activity All Things Tobago offers, with prices in TTD and USD.'">
    <section class="bg-ink text-white">
        <div class="wrap py-14 sm:py-20">
            <p class="eyebrow eyebrow-light">Experiences</p>
            <h1 class="h-section mt-3 !text-4xl sm:!text-5xl">{{ $cat ? $cat['name'] : 'Everything we do, in one place.' }}</h1>
            <p class="mt-4 max-w-2xl text-white/70">{{ $cat ? $cat['blurb'] : 'Prices are per person or per group, shown as "from". Children pay half on most boat trips. Switch to US$ in the top bar.' }}</p>
        </div>
    </section>
    <section class="section">
        <div class="wrap">
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('experiences') }}" class="chip {{ ! $category ? 'bg-ink text-white ring-ink' : '' }} !px-4 !py-2 !text-sm">All</a>
                @foreach ($categories as $key => $c)
                    <a href="{{ route('experiences', ['category' => $key]) }}" class="chip {{ $category === $key ? 'bg-ink text-white ring-ink' : '' }} !px-4 !py-2 !text-sm"><x-icon :name="$c['icon']" class="size-3.5" /> {{ $c['name'] }}</a>
                @endforeach
            </div>
            <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($experiences as $exp)
                    <x-experience-card :experience="$exp" :delay="$loop->index * 50" />
                @empty
                    <p class="text-muted">Nothing in this category yet.</p>
                @endforelse
            </div>
            <div class="mt-14 rounded-3xl bg-sea-50 p-6 ring-1 ring-sea-200 sm:p-8">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div><h2 class="h-card">Want two or three of these in one day?</h2><p class="mt-1 text-sm text-muted">The planner adds them up and sends one request.</p></div>
                    <a href="{{ route('planner') }}" class="btn btn-primary">Plan your day <x-icon name="arrow-right" class="size-4" /></a>
                </div>
            </div>
        </div>
    </section>
</x-layout>
