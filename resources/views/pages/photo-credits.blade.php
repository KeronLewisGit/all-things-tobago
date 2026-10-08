<x-layout title="Photo credits" description="Where the photos on this site come from.">
    <section class="bg-ink text-white"><div class="wrap py-14"><p class="eyebrow eyebrow-light">Photo credits</p><h1 class="h-section mt-3">The people behind the pictures.</h1></div></section>
    <section class="section"><div class="wrap max-w-3xl prose-site">
        <p>Some experience photos on this site are shared by photographers under Creative Commons licences via Wikimedia Commons. Thank you to each of them. Where a photo shows a place outside Tobago it is a stand-in until our own shot replaces it.</p>
        <ul>
            @foreach ($experiences as $experience)
                @php($credit = $credits[$experience->slug])
                <li>
                    <a href="{{ route('experiences.show', $experience) }}">{{ $experience->name }}</a>:
                    <a href="{{ $credit['source'] }}" rel="noopener" target="_blank">{{ $credit['title'] }}</a> by {{ $credit['author'] }},
                    @if ($credit['license_url'])<a href="{{ $credit['license_url'] }}" rel="license noopener" target="_blank">{{ $credit['license'] }}</a>@else {{ $credit['license'] }}@endif.
                </li>
            @endforeach
        </ul>
    </div></section>
</x-layout>
