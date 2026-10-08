<!DOCTYPE html>
<html lang="en">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Sign in · ATT bookings</title><meta name="robots" content="noindex">@vite(['resources/css/app.css', 'resources/js/app.js'])</head>
<body class="grid min-h-screen place-items-center bg-ink p-5">
    <form method="post" action="{{ route('login.attempt') }}" class="card w-full max-w-sm">
        @csrf
        <x-logo />
        <h1 class="mt-6 font-display text-2xl font-bold">Booking dashboard</h1>
        <p class="mt-1 text-sm text-muted">Sign in to see requests, the calendar and prices.</p>
        @if ($errors->any())<p class="mt-4 rounded-xl bg-rose-50 px-3 py-2 text-sm text-rose-800 ring-1 ring-rose-200">{{ $errors->first() }}</p>@endif
        <div class="mt-5 space-y-4">
            <x-field name="email" label="Email" type="email" required autocomplete="username" />
            <x-field name="password" label="Password" type="password" required autocomplete="current-password" />
            <label class="flex items-center gap-2 text-sm text-muted"><input type="checkbox" name="remember" value="1" class="size-4 rounded border-line"> Keep me signed in</label>
            <button class="btn btn-primary w-full">Sign in</button>
        </div>
    </form>
</body>
</html>
