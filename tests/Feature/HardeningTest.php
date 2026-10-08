<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Enquiry;
use App\Models\Experience;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class HardeningTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_booking_is_saved_even_if_the_alert_email_fails(): void
    {
        Notification::shouldReceive('send')->andThrow(new \RuntimeException('SMTP down'));
        $experience = Experience::active()->first();
        $before = Booking::count();

        $this->post(route('bookings.store', $experience), ['date' => now()->addDays(3)->format('Y-m-d'), 'adults' => 2, 'name' => 'Pat Guest', 'phone' => '868 555 0100'])->assertRedirect();

        $this->assertSame($before + 1, Booking::count());
        $this->assertStringContainsString('could not be sent', Booking::latest('id')->first()->activities()->pluck('body')->implode(' '));
    }

    public function test_enquiry_is_saved_even_if_the_alert_email_fails(): void
    {
        Notification::shouldReceive('send')->andThrow(new \RuntimeException('SMTP down'));
        $this->post(route('enquiries.store'), ['type' => 'stay', 'name' => 'Pat', 'email' => 'pat@example.com'])->assertRedirect();
        $this->assertSame(1, Enquiry::where('name', 'Pat')->count());
    }

    public function test_references_are_unique_and_prefixed(): void
    {
        $references = collect(range(1, 25))->map(fn () => Booking::create(['type' => 'single', 'date' => now()->addDay(), 'adults' => 1, 'name' => 'A', 'phone' => '1', 'estimate' => 0])->reference);
        $this->assertSame(25, $references->unique()->count());
        $this->assertTrue($references->every(fn ($r) => preg_match('/^ATT-[A-Z0-9]{6}$/', $r) === 1));
    }

    public function test_calendar_survives_a_bad_month_parameter(): void
    {
        $this->actingAs(User::first())->get(route('admin.calendar', ['month' => 'not-a-month']))->assertOk();
        $this->actingAs(User::first())->get(route('admin.calendar', ['month' => '2026-13']))->assertOk();
        $this->actingAs(User::first())->get(route('admin.calendar', ['month' => '2026-03']))->assertOk()->assertSee('March 2026');
    }

    public function test_weather_failure_is_cached_briefly(): void
    {
        Cache::put('weather.today', 'unavailable', 60);
        $this->get('/')->assertOk();
        $this->assertSame('unavailable', Cache::get('weather.today'), 'a failed lookup is not retried on every request');
    }
}
