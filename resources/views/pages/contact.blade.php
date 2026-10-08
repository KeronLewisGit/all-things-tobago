@php($site = config('site'))
<x-layout title="Contact" description="WhatsApp, call or message All Things Tobago in Crown Point. Open every day.">
    <section class="bg-ink text-white"><div class="wrap py-14 sm:py-20"><p class="eyebrow eyebrow-light">Contact</p><h1 class="h-section mt-3 !text-4xl sm:!text-5xl">Talk to the crew.</h1><p class="mt-4 max-w-2xl text-white/70">WhatsApp is fastest. The form reaches the same people, with a reference so nothing gets lost.</p></div></section>
    <section class="section"><div class="wrap grid gap-10 lg:grid-cols-[0.8fr_1fr] lg:items-start">
        <div class="space-y-5">
            <div class="card !p-6">
                <h2 class="h-card">Direct lines</h2>
                <div class="mt-4 space-y-3">
                    <a href="{{ \App\Support\Content::whatsapp('Hi All Things Tobago!') }}" target="_blank" rel="noopener" class="flex items-center gap-4 rounded-2xl bg-emerald-50 p-4 ring-1 ring-emerald-200 hover:bg-emerald-100"><span class="grid size-11 shrink-0 place-items-center rounded-xl bg-emerald-600 text-white"><x-icon name="message-circle" class="size-5" /></span><span class="min-w-0"><span class="block text-xs font-bold tracking-wider text-emerald-800 uppercase">WhatsApp</span><span class="block font-bold">{{ $site['phone'] }}</span></span></a>
                    <a href="tel:{{ $site['phone_href'] }}" class="flex items-center gap-4 rounded-2xl bg-paper p-4 ring-1 ring-line hover:bg-sea-50"><span class="grid size-11 shrink-0 place-items-center rounded-xl bg-sea-600 text-white"><x-icon name="phone" class="size-5" /></span><span class="min-w-0"><span class="block text-xs font-bold tracking-wider text-muted uppercase">Call</span><span class="block font-bold">{{ $site['phone'] }}</span></span></a>
                    <a href="mailto:{{ $site['email'] }}" class="flex items-center gap-4 rounded-2xl bg-paper p-4 ring-1 ring-line hover:bg-sea-50"><span class="grid size-11 shrink-0 place-items-center rounded-xl bg-ink text-white"><x-icon name="mail" class="size-5" /></span><span class="min-w-0"><span class="block text-xs font-bold tracking-wider text-muted uppercase">Email</span><span class="block font-bold break-all">{{ $site['email'] }}</span></span></a>
                    <a href="{{ $site['social']['instagram'] }}" target="_blank" rel="noopener" class="flex items-center gap-4 rounded-2xl bg-paper p-4 ring-1 ring-line hover:bg-sea-50"><span class="grid size-11 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-coral-500 to-sun-400 text-white"><x-icon name="instagram" class="size-5" /></span><span class="min-w-0"><span class="block text-xs font-bold tracking-wider text-muted uppercase">Instagram</span><span class="block font-bold">{{ $site['social']['instagram_handle'] }}</span></span></a>
                </div>
            </div>
            <div class="card !p-6"><h2 class="h-card">Hours and base</h2><ul class="mt-3 space-y-2 text-sm text-muted"><li class="flex gap-2"><x-icon name="clock" class="mt-0.5 size-4 shrink-0 text-sea-700" /> {{ $site['hours'] }}</li><li class="flex gap-2"><x-icon name="map-pin" class="mt-0.5 size-4 shrink-0 text-sea-700" /> {{ $site['base'] }}. {{ $site['pickup_note'] }}</li></ul></div>
        </div>
        <x-enquiry-form type="general" title="Send a message" intro="Group trips, press, partnerships, or anything else." :dates="false" />
    </div></section>
</x-layout>
