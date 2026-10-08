@props(['type', 'title', 'intro', 'dates' => true, 'budgets' => []])
@php($site = config('site'))
<div id="enquire">
    <x-form-status bag="enquiry" key="enquiry_success" />
    <form method="post" action="{{ route('enquiries.store') }}" class="card space-y-5">
        @csrf
        <input type="hidden" name="type" value="{{ $type }}">
        <div><h2 class="h-card">{{ $title }}</h2><p class="mt-1 text-sm text-muted">{{ $intro }}</p></div>
        <div class="grid gap-4 sm:grid-cols-2">
            <x-field name="name" label="Your name" bag="enquiry" required autocomplete="name" />
            <x-field name="phone" label="WhatsApp number" type="tel" bag="enquiry" autocomplete="tel" />
            <x-field name="email" label="Email" type="email" bag="enquiry" autocomplete="email" />
            <x-field name="guests" label="How many of you?" type="number" bag="enquiry" min="1" max="40" />
            @if ($dates)
                <x-field name="from" label="Arrive" type="date" bag="enquiry" />
                <x-field name="to" label="Leave" type="date" bag="enquiry" />
            @endif
            @if ($budgets)
                <x-field name="budget" label="Budget" type="select" bag="enquiry" :options="$budgets" placeholder="Choose" class="sm:col-span-2" />
            @endif
        </div>
        <x-field name="message" label="Tell us what you have in mind" type="textarea" bag="enquiry" required />
        <x-consent bag="enquiry" />
        <button class="btn btn-primary w-full">Send enquiry <x-icon name="arrow-right" class="size-4" /></button>
    </form>
</div>
