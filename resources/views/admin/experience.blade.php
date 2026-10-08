<x-admin :title="$experience->name">
    <a href="{{ route('admin.experiences') }}" class="link-arrow text-xs"><x-icon name="chevron-left" class="size-3.5" /> All experiences</a>
    <h1 class="mt-3 font-display text-3xl font-bold">{{ $experience->name }}</h1>
    <form method="post" action="{{ route('admin.experiences.update', $experience) }}" enctype="multipart/form-data" class="mt-6 grid gap-6 *:min-w-0 xl:grid-cols-[1fr_20rem]">
        @csrf @method('PATCH')
        <div class="space-y-5 rounded-3xl bg-white p-6 ring-1 ring-line/70">
            <div class="grid gap-4 sm:grid-cols-2">
                <x-field name="name" label="Name" :value="$experience->name" required class="sm:col-span-2" />
                <x-field name="category" label="Category" type="select" :options="collect($categories)->map(fn ($c) => $c['name'])->all()" :value="$experience->category" required />
                <x-field name="duration" label="Duration (text)" :value="$experience->duration" />
                <x-field name="tagline" label="Tagline" :value="$experience->tagline" class="sm:col-span-2" />
                <x-field name="description" label="Description" type="textarea" :value="$experience->description" class="sm:col-span-2" />
                <x-field name="highlights" label="Highlights (one per line)" type="textarea" :value="implode(PHP_EOL, $experience->highlights ?? [])" />
                <x-field name="includes" label="Included (one per line)" type="textarea" :value="implode(PHP_EOL, $experience->includes ?? [])" />
                <x-field name="excludes" label="Not included (one per line)" type="textarea" :value="implode(PHP_EOL, $experience->excludes ?? [])" />
                <x-field name="child_policy" label="Children" :value="$experience->child_policy" />
            </div>
            <button class="btn btn-primary">Save changes</button>
        </div>
        <div class="space-y-6">
            <div class="space-y-4 rounded-3xl bg-white p-5 ring-1 ring-line/70">
                <h2 class="font-display text-lg font-bold">Pricing</h2>
                <x-field name="price" label="From price (TTD)" type="number" :value="$experience->price" min="0" required />
                <x-field name="price_type" label="Charged" type="select" :options="['person' => 'Per person (children half)', 'group' => 'Per group (flat)']" :value="$experience->price_type" required />
                <div class="grid grid-cols-2 gap-3"><x-field name="min_guests" label="Min guests" type="number" :value="$experience->min_guests" min="1" required /><x-field name="max_guests" label="Max guests" type="number" :value="$experience->max_guests" min="1" required /></div>
                <x-field name="sort" label="Sort order" type="number" :value="$experience->sort" min="0" required hint="Lower shows first." />
                <label class="flex items-center gap-2 text-sm font-semibold"><input type="checkbox" name="featured" value="1" class="size-4 rounded border-line" @checked($experience->featured)> Show as Popular on the home page</label>
                <label class="flex items-center gap-2 text-sm font-semibold"><input type="checkbox" name="active" value="1" class="size-4 rounded border-line" @checked($experience->active)> Live on the website</label>
            </div>
            <div class="space-y-3 rounded-3xl bg-white p-5 ring-1 ring-line/70">
                <h2 class="font-display text-lg font-bold">Photo</h2>
                <div class="aspect-[4/3] overflow-hidden rounded-2xl"><x-scene :scene="$experience->scene" :image="$experience->imageUrl()" :alt="$experience->name" /></div>
                <p class="text-xs text-muted">{{ $experience->hasBundledImage() ? 'Stock photo in use. Upload your own landscape photo (JPG or WebP, up to 6 MB) to replace it.' : ($experience->image_path ? 'Uploaded photo in use.' : 'Illustration in use. Upload a landscape photo (JPG or WebP, up to 6 MB) to replace it.') }}</p>
                <input type="file" name="image" accept="image/*" class="block w-full text-sm">
                @if ($experience->image_path)<label class="flex items-center gap-2 text-sm"><input type="checkbox" name="remove_image" value="1" class="size-4 rounded border-line"> Remove photo, go back to the illustration</label>@endif
                <button class="btn btn-dark w-full">Save</button>
            </div>
        </div>
    </form>
</x-admin>
