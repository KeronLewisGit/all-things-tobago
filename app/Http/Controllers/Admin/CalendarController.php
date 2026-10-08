<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlackoutDate;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(Request $request): View
    {
        $month = Carbon::createFromFormat('Y-m', (string) $request->query('month', now()->format('Y-m')))->startOfMonth();
        $start = $month->copy()->startOfWeek(Carbon::MONDAY);
        $end = $month->copy()->endOfMonth()->endOfWeek(Carbon::SUNDAY);

        $bookings = Booking::open()->with('experience')->whereBetween('date', [$start, $end])->orderBy('time_slot')->get()->groupBy(fn ($b) => $b->date->toDateString());
        $blackouts = BlackoutDate::whereBetween('date', [$start, $end])->get()->keyBy(fn ($b) => $b->date->toDateString());

        $days = [];
        for ($d = $start->copy(); $d <= $end; $d->addDay()) {
            $days[] = ['date' => $d->copy(), 'inMonth' => $d->month === $month->month, 'bookings' => $bookings[$d->toDateString()] ?? collect(), 'blackout' => $blackouts[$d->toDateString()] ?? null];
        }

        return view('admin.calendar', ['month' => $month, 'days' => $days, 'blackouts' => BlackoutDate::whereDate('date', '>=', today())->orderBy('date')->get()]);
    }

    public function storeBlackout(Request $request): RedirectResponse
    {
        $data = $request->validate(['date' => ['required', 'date'], 'reason' => ['nullable', 'string', 'max:120']]);
        BlackoutDate::updateOrCreate(['date' => $data['date']], ['reason' => $data['reason'] ?? null]);

        return back()->with('saved', true);
    }

    public function destroyBlackout(BlackoutDate $blackout): RedirectResponse
    {
        $blackout->delete();

        return back()->with('saved', true);
    }
}
