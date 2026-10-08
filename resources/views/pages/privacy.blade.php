@php($site = config('site'))
<x-layout title="Privacy" description="How All Things Tobago handles booking details.">
    <section class="bg-ink text-white"><div class="wrap py-14"><p class="eyebrow eyebrow-light">Privacy</p><h1 class="h-section mt-3">Your details, handled simply.</h1></div></section>
    <section class="section"><div class="wrap max-w-3xl prose-site">
        <p><strong>What we collect.</strong> When you send a booking request or enquiry we keep your name, phone number, email if you give one, the dates and group size, and anything you write in the notes. We also record how you found the site (for example, Instagram) so we know what is working.</p>
        <p><strong>What we do with it.</strong> We use it to confirm your booking, send pickup details and answer your questions. We share it only with the captain, driver, therapist or host who delivers your experience, and only what they need. We do not sell it or add you to marketing lists.</p>
        <p><strong>How long we keep it.</strong> Booking records are kept for accounting and insurance purposes. Ask at any time to see, correct or delete what we hold by emailing <span class="break-all">{{ $site['email'] }}</span>.</p>
        <p><strong>Cookies.</strong> The site uses a session cookie to remember your form and your currency choice. There is no advertising tracking.</p>
        <p><strong>Third parties.</strong> WhatsApp links open the WhatsApp app under its own terms. The weather strip uses the Open-Meteo public forecast service and sends no personal data.</p>
    </div></section>
</x-layout>
