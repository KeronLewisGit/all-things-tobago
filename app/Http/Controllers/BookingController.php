<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookingRequest;
use App\Models\Booking;
use App\Models\Experience;
use App\Models\User;
use App\Notifications\NewBookingNotification;
use App\Support\Content;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /** Single-experience booking request from an experience page. */
    public function store(StoreBookingRequest $request, Experience $experience): RedirectResponse
    {
        abort_unless($experience->active, 404);

        if ($request->isSpam()) {
            return back()->with('booking_success', ['reference' => null, 'name' => 'friend']);
        }

        $data = $request->validated();
        $booking = $this->create($request, $data + ['type' => 'single', 'experience_id' => $experience->id, 'estimate' => $experience->estimate((int) $data['adults'], (int) ($data['children'] ?? 0))]);

        return redirect()->to(route('experiences.show', $experience).'#book')->with('booking_success', $this->flash($booking));
    }

    /** Multi-experience request from the trip planner. */
    public function planner(StoreBookingRequest $request): RedirectResponse
    {
        if ($request->isSpam()) {
            return back()->with('booking_success', ['reference' => null, 'name' => 'friend']);
        }

        $data = $request->validated();
        $experiences = Experience::active()->whereIn('slug', $data['items'] ?? [])->get();

        if ($experiences->isEmpty()) {
            return back()->withErrors(['items' => 'Pick at least one experience for your day.'], 'booking')->withInput();
        }

        $adults = (int) $data['adults'];
        $children = (int) ($data['children'] ?? 0);
        $items = $experiences->map(fn (Experience $e) => ['slug' => $e->slug, 'name' => $e->name, 'price' => $e->price, 'price_type' => $e->price_type, 'subtotal' => $e->estimate($adults, $children)])->values()->all();

        $booking = $this->create($request, $data + ['type' => 'planner', 'items' => $items, 'estimate' => array_sum(array_column($items, 'subtotal'))]);

        return redirect()->to(route('planner').'#plan')->with('booking_success', $this->flash($booking));
    }

    protected function create(StoreBookingRequest $request, array $data): Booking
    {
        $booking = Booking::create(array_merge($data, [
            'children' => $data['children'] ?? 0,
            'contact_via' => $data['contact_via'] ?? 'whatsapp',
            'source' => array_merge($request->session()->get('attribution', []), ['device' => preg_match('/Mobile|Android|iPhone/i', (string) $request->userAgent()) ? 'mobile' : 'desktop', 'user_agent' => Str::limit((string) $request->userAgent(), 200, '')]),
        ]));
        $booking->activities()->create(['type' => 'created', 'body' => 'Request received from the website.']);

        if ($recipient = User::first()) {
            $recipient->notify(new NewBookingNotification($booking));
        }

        return $booking;
    }

    protected function flash(Booking $booking): array
    {
        $message = "Hi All Things Tobago, I just sent booking request {$booking->reference} for {$booking->title()} on {$booking->date->format('D j M')} ({$booking->guests()} guests). Can you confirm availability?";

        return ['reference' => $booking->reference, 'name' => Str::before($booking->name, ' '), 'whatsapp' => Content::whatsapp($message), 'estimate' => $booking->estimate];
    }
}
