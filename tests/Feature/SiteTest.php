<?php

namespace Tests\Feature;

use App\Models\BlackoutDate;
use App\Models\Booking;
use App\Models\Experience;
use App\Models\User;
use App\Notifications\NewBookingNotification;
use Database\Seeders\ExperienceSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class SiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(ExperienceSeeder::class);
    }

    public static function publicPages(): array
    {
        return [['/'], ['/experiences'], ['/experiences?category=boat-tours'], ['/experiences/360-island-tour'], ['/plan-your-day'], ['/stay'], ['/events'], ['/about'], ['/faq'], ['/contact'], ['/links'], ['/privacy'], ['/terms'], ['/sitemap.xml'], ['/robots.txt'], ['/admin/login']];
    }

    #[DataProvider('publicPages')]
    public function test_public_pages_render(string $path): void
    {
        $this->get($path)->assertOk();
    }

    public function test_unknown_experience_and_category_404(): void
    {
        $this->get('/experiences/nope')->assertNotFound();
        $this->get('/experiences?category=nope')->assertNotFound();
    }

    public function test_booking_request_is_stored_with_estimate_and_notifies_admin(): void
    {
        Notification::fake();
        $admin = User::factory()->create();
        $reef = Experience::where('slug', 'buccoo-reef-nylon-pool-no-mans-land')->first();

        $response = $this->from(route('experiences.show', $reef))->post(route('bookings.store', $reef), [
            'date' => now()->addDays(5)->toDateString(), 'time_slot' => 'morning', 'adults' => 2, 'children' => 1,
            'name' => 'Test Guest', 'phone' => '+1 868 555 0100', 'email' => 'guest@example.com', 'pickup' => 'Crown Point', 'notes' => 'Birthday',
        ]);

        $response->assertRedirect(route('experiences.show', $reef).'#book')->assertSessionHas('booking_success');
        $booking = Booking::first();
        $this->assertSame((int) round(245 * 2 + 245 * 0.5), $booking->estimate);
        $this->assertStringStartsWith('ATT-', $booking->reference);
        $this->assertCount(1, $booking->activities);
        Notification::assertSentTo($admin, NewBookingNotification::class);
    }

    public function test_blackout_dates_are_refused(): void
    {
        $date = now()->addDays(3)->toDateString();
        BlackoutDate::create(['date' => $date, 'reason' => 'Day off']);
        $exp = Experience::first();

        $this->post(route('bookings.store', $exp), ['date' => $date, 'adults' => 2, 'name' => 'A', 'phone' => '1'])->assertSessionHasErrors('date', null, 'booking');
        $this->assertSame(0, Booking::count());
    }

    public function test_planner_sums_several_experiences(): void
    {
        $this->post(route('planner.store'), [
            'items' => ['double-clear-kayaking', 'beach-massages'], 'date' => now()->addDays(2)->toDateString(), 'adults' => 2, 'children' => 0, 'name' => 'Pair', 'phone' => '+1 868 555 0101',
        ])->assertRedirect(route('planner').'#plan');

        $booking = Booking::first();
        $this->assertSame('planner', $booking->type);
        $this->assertSame(350 + 700 * 2, $booking->estimate);
        $this->assertCount(2, $booking->items);
    }

    public function test_honeypot_drops_spam_silently(): void
    {
        $exp = Experience::first();
        $this->post(route('bookings.store', $exp), ['website' => 'http://spam', 'date' => now()->addDay()->toDateString(), 'adults' => 1, 'name' => 'Bot', 'phone' => '0'])->assertRedirect();
        $this->assertSame(0, Booking::count());
    }

    public function test_enquiry_is_stored(): void
    {
        $this->post(route('enquiries.store'), ['type' => 'stay', 'name' => 'Guest', 'phone' => '+1 868 555 0102', 'guests' => 4, 'from' => now()->addMonth()->toDateString(), 'to' => now()->addMonth()->addDays(5)->toDateString(), 'message' => 'Villa with a pool please'])
            ->assertRedirect()->assertSessionHas('enquiry_success');
        $this->assertDatabaseHas('enquiries', ['type' => 'stay', 'name' => 'Guest']);
    }

    public function test_admin_requires_login_and_dashboard_renders(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
        $user = User::factory()->create();
        $this->actingAs($user)->get('/admin')->assertOk();
        $this->actingAs($user)->get('/admin/bookings')->assertOk();
        $this->actingAs($user)->get('/admin/calendar')->assertOk();
        $this->actingAs($user)->get('/admin/experiences')->assertOk();
        $this->actingAs($user)->get('/admin/reviews')->assertOk();
        $this->actingAs($user)->get('/admin/gallery')->assertOk();
        $this->actingAs($user)->get('/admin/enquiries')->assertOk();
    }

    public function test_admin_can_move_a_booking_and_block_a_date(): void
    {
        $user = User::factory()->create();
        $exp = Experience::first();
        $booking = Booking::create(['experience_id' => $exp->id, 'date' => now()->addDays(4), 'adults' => 2, 'name' => 'Guest', 'phone' => '1', 'estimate' => 500]);

        $this->actingAs($user)->patch(route('admin.bookings.update', $booking), ['status' => 'confirmed'])->assertRedirect();
        $this->assertSame('confirmed', $booking->fresh()->status);
        $this->assertNotNull($booking->fresh()->confirmed_at);

        $this->actingAs($user)->post(route('admin.blackouts.store'), ['date' => now()->addDays(9)->toDateString(), 'reason' => 'Maintenance'])->assertRedirect();
        $this->assertDatabaseCount('blackout_dates', 1);
        $this->actingAs($user)->get(route('admin.bookings.show', $booking))->assertOk()->assertSee('Guest');
    }
}
