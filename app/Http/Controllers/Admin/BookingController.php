<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Support\Content;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookingController extends Controller
{
    public function index(Request $request): View
    {
        $query = Booking::with('experience');

        if ($status = $request->query('status')) {
            $status === 'open' ? $query->open() : $query->where('status', $status);
        }
        if ($q = trim((string) $request->query('q'))) {
            $query->where(fn ($w) => $w->where('name', 'like', "%{$q}%")->orWhere('reference', 'like', "%{$q}%")->orWhere('phone', 'like', "%{$q}%")->orWhere('email', 'like', "%{$q}%"));
        }
        if ($request->query('when') === 'upcoming') {
            $query->whereDate('date', '>=', today())->orderBy('date');
        } else {
            $query->latest();
        }

        return view('admin.bookings', [
            'bookings' => $query->paginate(25)->withQueryString(),
            'counts' => ['all' => Booking::count(), 'new' => Booking::where('status', 'new')->count(), 'open' => Booking::open()->count()] + Booking::select('status')->selectRaw('count(*) as c')->groupBy('status')->pluck('c', 'status')->all(),
        ]);
    }

    public function show(Booking $booking): View
    {
        $booking->load('experience', 'activities.user');
        $msg = "Hi {$booking->name}, All Things Tobago here about your request {$booking->reference} for {$booking->title()} on {$booking->date->format('l j F')}.";

        return view('admin.booking', ['booking' => $booking, 'whatsapp' => 'https://wa.me/'.preg_replace('/\D+/', '', $booking->phone).'?text='.rawurlencode($msg), 'ttd' => Content::ttd($booking->estimate)]);
    }

    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['nullable', Rule::in(array_keys(Booking::STATUSES))],
            'note' => ['nullable', 'string', 'max:2000'],
            'note_type' => ['nullable', Rule::in(['note', 'whatsapp', 'call', 'email'])],
            'estimate' => ['nullable', 'integer', 'min:0', 'max:1000000'],
        ]);

        if (! empty($data['status']) && $data['status'] !== $booking->status) {
            $booking->status = $data['status'];
            if (in_array($data['status'], ['confirmed', 'paid']) && ! $booking->confirmed_at) {
                $booking->confirmed_at = now();
            }
            $booking->activities()->create(['user_id' => $request->user()->id, 'type' => 'status', 'body' => 'Moved to '.$booking->statusLabel().'.']);
        }
        if (isset($data['estimate']) && (int) $data['estimate'] !== $booking->estimate) {
            $booking->activities()->create(['user_id' => $request->user()->id, 'type' => 'status', 'body' => 'Price updated from TT$'.number_format($booking->estimate).' to TT$'.number_format($data['estimate']).'.']);
            $booking->estimate = (int) $data['estimate'];
        }
        if (! empty($data['note'])) {
            $booking->activities()->create(['user_id' => $request->user()->id, 'type' => $data['note_type'] ?? 'note', 'body' => $data['note']]);
        }
        $booking->save();

        return back()->with('saved', true);
    }

    public function destroy(Booking $booking): RedirectResponse
    {
        $booking->delete();

        return redirect()->route('admin.bookings')->with('saved', true);
    }

    public function export(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Reference', 'Status', 'Date', 'Slot', 'Experience', 'Adults', 'Children', 'Name', 'Phone', 'Email', 'Pickup', 'Estimate TTD', 'Source', 'Requested']);
            Booking::with('experience')->orderByDesc('date')->chunk(200, function ($rows) use ($out) {
                foreach ($rows as $b) {
                    fputcsv($out, [$b->reference, $b->statusLabel(), $b->date->toDateString(), $b->time_slot, $b->title(), $b->adults, $b->children, $b->name, $b->phone, $b->email, $b->pickup, $b->estimate, $b->source['utm_source'] ?? '', $b->created_at->toDateTimeString()]);
                }
            });
            fclose($out);
        }, 'bookings-'.now()->toDateString().'.csv', ['Content-Type' => 'text/csv']);
    }
}
