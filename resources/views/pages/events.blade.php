<x-layout title="Events and tickets in Tobago" description="Tickets and transport for fetes, boat parties, Sunday School in Buccoo and festival events across Tobago, through All Things Tobago.">
    <section class="bg-ink text-white"><div class="wrap py-14 sm:py-20"><p class="eyebrow eyebrow-light">Events and tickets</p><h1 class="h-section mt-3 !text-4xl sm:!text-5xl">What's on, and how to get there.</h1><p class="mt-4 max-w-2xl text-white/70">Boat parties, Sunday School in Buccoo, Jazz, Heritage Festival, goat races at Easter and the fetes in between. We hold tickets for the big ones and run transport so nobody has to drive.</p></div></section>
    <section class="section">
        <div class="wrap grid gap-10 lg:grid-cols-[1fr_0.9fr] lg:items-start">
            <div class="space-y-5">
                @foreach ([['music', 'Sunday School, Buccoo', 'Every Sunday night', 'Steelpan, street food and the island dancing in one village. We run a pickup loop from Crown Point from 8 pm.'], ['ship', 'Boat parties', 'Most weekends in season', 'Day and sunset cruises out of Pigeon Point with DJ and drinks. Tickets sell out, ask early.'], ['ticket', 'Festivals', 'Jazz (April), Heritage (July to August), Blue Food (October)', 'Tickets plus transport to venues around the island, with a stop for food on the way back.'], ['sun', 'Beach limes and fetes', 'Carnival season and public holidays', 'We know which ones are worth it. Message for this week\'s list.']] as [$icon, $title, $when, $body])
                    <div class="card flex gap-5 !p-5" data-reveal style="--reveal-delay: {{ $loop->index * 60 }}ms"><span class="icon-tile"><x-icon :name="$icon" class="size-6" /></span><div><h3 class="h-card">{{ $title }}</h3><p class="mt-0.5 text-xs font-bold text-sea-700">{{ $when }}</p><p class="mt-2 text-sm text-muted">{{ $body }}</p></div></div>
                @endforeach
                <p class="text-sm text-muted">The admin dashboard lets the team post upcoming events with ticket prices here. For now, ask on WhatsApp for this week's list.</p>
            </div>
            <x-enquiry-form type="tickets" title="Get me tickets" intro="Tell us the event or the dates you are here and we will send what's on with prices." :dates="true" />
        </div>
    </section>
</x-layout>
