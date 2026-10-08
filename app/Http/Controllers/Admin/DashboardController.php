<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Enquiry;
use App\Models\Experience;
use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $monthStart = now()->startOfMonth();

        $byDay = Booking::where('created_at', '>=', now()->subDays(29)->startOfDay())->select(DB::raw('date(created_at) as d'), DB::raw('count(*) as c'))->groupBy('d')->pluck('c', 'd');
        $days = collect(range(29, 0))->map(fn ($i) => now()->subDays($i)->toDateString())->map(fn ($d) => ['date' => $d, 'count' => (int) ($byDay[$d] ?? 0)]);

        return view('admin.dashboard', [
            'newCount' => Booking::where('status', 'new')->count(),
            'todayCount' => Booking::open()->whereDate('date', today())->count(),
            'monthRequests' => Booking::where('created_at', '>=', $monthStart)->count(),
            'monthRevenue' => (int) Booking::whereIn('status', ['paid', 'completed'])->where('date', '>=', $monthStart)->sum('estimate'),
            'pipelineRevenue' => (int) Booking::open()->whereDate('date', '>=', today())->sum('estimate'),
            'pendingReviews' => Review::where('approved', false)->count(),
            'newEnquiries' => Enquiry::where('status', 'new')->count(),
            'upcoming' => Booking::upcoming()->with('experience')->take(8)->get(),
            'recent' => Booking::with('experience')->latest()->take(8)->get(),
            'days' => $days,
            'topExperiences' => Experience::withCount(['bookings' => fn ($q) => $q->where('created_at', '>=', now()->subDays(60))])->orderByDesc('bookings_count')->take(6)->get(),
            'sources' => Booking::where('created_at', '>=', now()->subDays(60))->get()->groupBy(fn ($b) => $b->source['utm_source'] ?? ($b->source['referrer_host'] ?? 'Direct'))->map->count()->sortDesc()->take(6),
        ]);
    }
}
