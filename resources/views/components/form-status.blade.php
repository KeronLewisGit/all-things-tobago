@props(['bag' => 'booking', 'key' => 'booking_success'])
@php($success = session($key))
@if ($success)
    <div class="mb-6 rounded-3xl bg-emerald-50 p-5 text-emerald-950 ring-1 ring-emerald-200 sm:p-6" role="status">
        <div class="flex gap-4">
            <span class="grid size-11 shrink-0 place-items-center rounded-full bg-emerald-600 text-white"><x-icon name="check" class="size-5" /></span>
            <div class="min-w-0">
                <p class="font-display text-2xl font-bold">{{ ($success['name'] ?? null) ? 'Got it, '.$success['name'].'.' : 'Got it.' }}</p>
                <p class="mt-1 text-sm text-emerald-900/80">
                    @if ($success['reference'] ?? null)
                        Your reference is <strong class="font-bold">{{ $success['reference'] }}</strong>. We check availability and reply on WhatsApp, usually within the hour during the day. Nothing is charged until we confirm.
                    @else
                        We will be in touch shortly.
                    @endif
                </p>
                @if ($success['whatsapp'] ?? null)
                    <a href="{{ $success['whatsapp'] }}" target="_blank" rel="noopener" class="btn btn-whatsapp mt-4 !py-2.5"><x-icon name="message-circle" class="size-4" /> Fast-track it on WhatsApp</a>
                @endif
            </div>
        </div>
    </div>
@elseif ($errors->getBag($bag)->any())
    <div class="mb-6 flex gap-3 rounded-2xl bg-rose-50 p-4 text-sm text-rose-900 ring-1 ring-rose-200" role="alert">
        <x-icon name="triangle-alert" class="mt-0.5 size-5 shrink-0 text-rose-600" />
        <p><strong class="font-semibold">Please check the form.</strong> {{ $errors->getBag($bag)->first() }}</p>
    </div>
@endif
