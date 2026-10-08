@php($site = config('site'))
<x-layout title="Questions answered" description="Booking, payment, pickup, weather and children on Tobago tours and boat trips with All Things Tobago." :schema="[['@type' => 'FAQPage', 'mainEntity' => array_map(fn ($f) => ['@type' => 'Question', 'name' => $f['q'], 'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['a']]], $site['faqs'])]]">
    <section class="bg-ink text-white"><div class="wrap py-14 sm:py-20"><p class="eyebrow eyebrow-light">FAQ</p><h1 class="h-section mt-3 !text-4xl sm:!text-5xl">Questions, answered plainly.</h1><p class="mt-4 max-w-2xl text-white/70">If yours is not here, WhatsApp us. We answer fast and we will add it to the list.</p></div></section>
    <section class="section"><div class="wrap grid gap-10 lg:grid-cols-[1fr_0.5fr] lg:items-start">
        <div class="divide-y divide-line rounded-3xl bg-white ring-1 ring-line/70" x-data="{ open: 0 }">
            @foreach ($site['faqs'] as $faq)
                <details class="group px-6 py-5" {{ $loop->first ? 'open' : '' }}>
                    <summary class="flex cursor-pointer items-center justify-between gap-4 font-bold"><span>{{ $faq['q'] }}</span><span class="grid size-8 shrink-0 place-items-center rounded-full bg-paper-deep text-sea-700 transition group-open:rotate-180"><x-icon name="chevron-down" class="size-4" /></span></summary>
                    <p class="mt-3 text-[15px] leading-relaxed text-muted">{{ $faq['a'] }}</p>
                </details>
            @endforeach
        </div>
        <div class="card bg-sea-50 ring-sea-200"><h2 class="h-card">Still unsure?</h2><p class="mt-2 text-sm text-muted">Send your dates and group and we will suggest a plan with prices.</p><div class="mt-5 flex flex-col gap-2"><a href="{{ \App\Support\Content::whatsapp('Hi! I have a question about booking with All Things Tobago.') }}" target="_blank" rel="noopener" class="btn btn-whatsapp"><x-icon name="message-circle" class="size-4" /> WhatsApp</a><a href="{{ route('planner') }}" class="btn btn-light">Plan a day</a></div></div>
    </div></section>
</x-layout>
