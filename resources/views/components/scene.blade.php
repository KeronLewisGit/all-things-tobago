@props(['scene' => 'reef', 'image' => null, 'alt' => ''])
{{-- Illustrated placeholder art per experience type. When the client uploads a photo it replaces the illustration. --}}
@if ($image)
    <img src="{{ $image }}" alt="{{ $alt }}" {{ $attributes->merge(['class' => 'h-full w-full object-cover']) }} loading="lazy" decoding="async">
@else
    @php($id = 'g'.substr(md5($scene.uniqid()), 0, 6))
    <svg viewBox="0 0 400 300" preserveAspectRatio="xMidYMid slice" {{ $attributes->merge(['class' => 'h-full w-full']) }} role="img" aria-label="{{ $alt ?: $scene }}">
        <defs>
            @switch($scene)
                @case('sunset')
                    <linearGradient id="{{ $id }}" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#ff9a5c"/><stop offset=".45" stop-color="#ff5c6a"/><stop offset=".62" stop-color="#8a3a8f"/><stop offset="1" stop-color="#1b2a5a"/></linearGradient>
                    @break
                @case('forest')
                    <linearGradient id="{{ $id }}" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#5fbf7a"/><stop offset=".5" stop-color="#1f8a5b"/><stop offset="1" stop-color="#0f4d3a"/></linearGradient>
                    @break
                @case('night')
                    <linearGradient id="{{ $id }}" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#0b1535"/><stop offset=".6" stop-color="#13275a"/><stop offset="1" stop-color="#0a3a4a"/></linearGradient>
                    @break
                @case('island')
                    <linearGradient id="{{ $id }}" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#1dc2bc"/><stop offset=".55" stop-color="#0b8886"/><stop offset="1" stop-color="#0c4a56"/></linearGradient>
                    @break
                @case('spa')
                    <linearGradient id="{{ $id }}" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#8fe3dc"/><stop offset=".5" stop-color="#ffd9b8"/><stop offset="1" stop-color="#f0c48e"/></linearGradient>
                    @break
                @case('fishing')
                    <linearGradient id="{{ $id }}" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#9ad7ff"/><stop offset=".5" stop-color="#1e6fb8"/><stop offset="1" stop-color="#0a2d5c"/></linearGradient>
                    @break
                @case('speed')
                    <linearGradient id="{{ $id }}" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#5de0ff"/><stop offset=".55" stop-color="#0aa7c9"/><stop offset="1" stop-color="#05527a"/></linearGradient>
                    @break
                @default
                    <linearGradient id="{{ $id }}" x1="0" y1="0" x2="0" y2="1"><stop offset="0" stop-color="#7ff0e6"/><stop offset=".5" stop-color="#12b3ae"/><stop offset="1" stop-color="#0b5d66"/></linearGradient>
            @endswitch
        </defs>
        <rect width="400" height="300" fill="url(#{{ $id }})"/>
        @switch($scene)
            @case('sunset')
                <circle cx="300" cy="150" r="46" fill="#ffd166" opacity=".95"/>
                <rect y="168" width="400" height="132" fill="#1b2a5a" opacity=".55"/>
                <path d="M0 200 Q50 190 100 200 T200 200 T300 200 T400 200 V300 H0Z" fill="#0f1b3f" opacity=".6"/>
                <path d="M60 300 V210 M60 210 q-30 -25 -55 -10 M60 210 q10 -35 40 -30 M60 210 q30 -20 55 5 M60 210 q-5 -30 -30 -40" stroke="#06101f" stroke-width="6" fill="none" stroke-linecap="round"/>
                @break
            @case('forest')
                <path d="M170 0 h60 v300 h-60z" fill="#e8fbff" opacity=".55"/>
                <path d="M185 0 h30 v300 h-30z" fill="#ffffff" opacity=".7"/>
                <ellipse cx="200" cy="300" rx="150" ry="28" fill="#bff3ff" opacity=".6"/>
                <path d="M0 60 q60 -40 120 0 q-60 30 -120 0z M280 40 q60 -40 120 0 q-60 30 -120 0z M40 160 q50 -35 100 0 q-50 30 -100 0z M300 170 q50 -35 100 0 q-50 30 -100 0z" fill="#063b2c" opacity=".7"/>
                @break
            @case('night')
                @foreach ([[30,40],[90,20],[150,60],[220,30],[300,50],[360,25],[70,110],[340,120],[200,100],[260,80],[120,140]] as [$x, $y])
                    <circle cx="{{ $x }}" cy="{{ $y }}" r="{{ $loop->index % 3 + 1 }}" fill="#fff" opacity=".8"/>
                @endforeach
                <circle cx="330" cy="70" r="26" fill="#fff6d5"/>
                <circle cx="342" cy="60" r="24" fill="#0f1f4b"/>
                <ellipse cx="200" cy="250" rx="90" ry="22" fill="#6fe9ff" opacity=".35"/>
                <path d="M120 240 q80 -26 160 0 q-80 20 -160 0z" fill="#9ff5ff" opacity=".9"/>
                @break
            @case('island')
                <path d="M110 190 c-20 -40 10 -90 60 -100 c40 -8 70 10 110 0 c40 -10 60 30 50 70 c-10 40 -60 50 -100 60 c-50 12 -100 10 -120 -30z" fill="#2ea36a"/>
                <path d="M140 170 c-10 -30 10 -60 45 -65 c30 -5 55 8 85 0 c30 -8 45 20 38 50 c-8 30 -45 35 -75 45 c-40 10 -80 5 -93 -30z" fill="#6fcf8c" opacity=".8"/>
                <circle cx="130" cy="120" r="5" fill="#ff5c3a"/><circle cx="250" cy="90" r="5" fill="#ff5c3a"/><circle cx="310" cy="190" r="5" fill="#ff5c3a"/>
                <path d="M130 120 Q190 60 250 90 T310 190" stroke="#ffd166" stroke-width="3" stroke-dasharray="6 6" fill="none"/>
                @break
            @case('spa')
                <ellipse cx="200" cy="300" rx="260" ry="80" fill="#f6e3c0"/>
                <path d="M250 110 l-90 0 a90 90 0 0 1 180 0z" fill="#ff7a59"/>
                <path d="M250 110 l-90 0 a90 90 0 0 1 90 -90z" fill="#ffd166"/>
                <rect x="248" y="110" width="4" height="150" fill="#5b3a1e"/>
                <ellipse cx="120" cy="225" rx="60" ry="14" fill="#ffffff" opacity=".9"/>
                @break
            @case('fishing')
                <rect y="190" width="400" height="110" fill="#0a2d5c" opacity=".45"/>
                <path d="M110 190 h150 l-20 30 h-110z" fill="#f7f1e3"/>
                <rect x="150" y="150" width="60" height="40" rx="6" fill="#ffffff"/>
                <path d="M210 160 L330 90" stroke="#fff" stroke-width="2"/><path d="M330 90 v120" stroke="#fff" stroke-width="1.5" stroke-dasharray="3 4"/>
                <path d="M320 220 q10 -10 20 0 q-10 10 -20 0z" fill="#ffd166"/>
                @break
            @case('speed')
                <path d="M0 220 Q60 200 120 220 T240 220 T360 220 T480 220 V300 H0z" fill="#ffffff" opacity=".25"/>
                <path d="M150 215 l90 -10 l30 20 h-130z" fill="#ff5c3a"/>
                <path d="M230 205 l-20 -22 h-30 l10 22z" fill="#06212f"/>
                <path d="M110 240 q-40 -10 -80 0 M140 255 q-60 -12 -120 0" stroke="#fff" stroke-width="5" stroke-linecap="round" fill="none" opacity=".8"/>
                @break
            @default
                <circle cx="330" cy="60" r="30" fill="#ffd166" opacity=".9"/>
                <path d="M0 150 Q50 135 100 150 T200 150 T300 150 T400 150" stroke="#fff" stroke-width="3" fill="none" opacity=".5"/>
                <path d="M0 190 Q50 175 100 190 T200 190 T300 190 T400 190" stroke="#fff" stroke-width="3" fill="none" opacity=".35"/>
                <path d="M120 230 h160 l-18 26 h-124z" fill="#f7f1e3"/><rect x="190" y="200" width="6" height="30" fill="#06212f"/>
                <ellipse cx="200" cy="272" rx="150" ry="18" fill="#073b45" opacity=".35"/>
        @endswitch
    </svg>
@endif
