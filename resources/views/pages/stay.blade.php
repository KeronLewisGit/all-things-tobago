<x-layout title="Places to stay in Tobago" description="Villas, guesthouses and apartments near Crown Point and Buccoo, arranged with your tours by All Things Tobago.">
    <section class="bg-ink text-white"><div class="wrap py-14 sm:py-20"><p class="eyebrow eyebrow-light">Accommodation</p><h1 class="h-section mt-3 !text-4xl sm:!text-5xl">Stay where the boats leave from.</h1><p class="mt-4 max-w-2xl text-white/70">We know the owners. Villas with pools, beachfront apartments and guesthouses five minutes from Pigeon Point and Store Bay, matched to your group and budget, with tours and airport pickup rolled in.</p></div></section>
    <section class="section">
        <div class="wrap grid gap-10 lg:grid-cols-[1fr_0.9fr] lg:items-start">
            <div class="space-y-8">
                <div class="grid gap-4 sm:grid-cols-3">
                    @foreach ([['bed-double', 'Villas', 'Three to six bedrooms, private pool, cook on request. Groups and families.'], ['umbrella', 'Beachfront', 'Apartments and small hotels on or across from the sand at Store Bay and Crown Point.'], ['heart', 'Couples', 'Guesthouses and boutique rooms in Buccoo, Black Rock and Castara.']] as [$icon, $title, $body])
                        <div class="card !p-5" data-reveal><span class="icon-tile"><x-icon :name="$icon" class="size-6" /></span><h3 class="h-card mt-4">{{ $title }}</h3><p class="mt-1.5 text-sm text-muted">{{ $body }}</p></div>
                    @endforeach
                </div>
                <div class="rounded-3xl bg-sea-50 p-6 ring-1 ring-sea-200" data-reveal>
                    <h2 class="h-card">How it works</h2>
                    <ol class="mt-3 space-y-2 text-sm">
                        <li class="flex gap-2"><span class="font-bold text-sea-700">1.</span> Send your dates, group size and budget below.</li>
                        <li class="flex gap-2"><span class="font-bold text-sea-700">2.</span> We reply on WhatsApp with two or three options, photos and prices.</li>
                        <li class="flex gap-2"><span class="font-bold text-sea-700">3.</span> Book direct with the owner or through us. Add airport pickup and tours to the same chat.</li>
                    </ol>
                </div>
            </div>
            <x-enquiry-form type="stay" title="Find me a place" intro="No fee for the search. We only suggest places we have been inside." :budgets="['Under TT$500 a night' => 'Under TT$500 a night', 'TT$500 to 1,000' => 'TT$500 to 1,000', 'TT$1,000 to 2,000' => 'TT$1,000 to 2,000', 'Over TT$2,000' => 'Over TT$2,000']" />
        </div>
    </section>
</x-layout>
