<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Experience;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Demo data for local development and the client preview. On production run only
 * `php artisan db:seed --class=ExperienceSeeder` and `php artisan site:admin`.
 */
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(ExperienceSeeder::class);

        User::firstOrCreate(['email' => config('site.email')], ['name' => 'All Things Tobago', 'password' => 'password']);

        if (Review::count() === 0) {
            $reviews = [
                ['buccoo-reef-nylon-pool-no-mans-land', 'Keisha M.', 'Port of Spain', 5, 'Best boat trip we have done in Tobago. The captain knew every fish on the reef and the Nylon Pool stop was longer than other tours give you.'],
                ['360-island-tour', 'Daniel & Priya', 'London, UK', 5, 'Eight hours flew by. Our guide stopped for roti in Castara, took us to a waterfall we would never have found and got us back for sunset.'],
                ['double-clear-kayaking', 'Shanice R.', 'Toronto, Canada', 5, 'The clear kayak photos are the ones everyone asked about when we got home. Turtles right underneath us.'],
                ['clear-kayak-floral-shoot', 'Marcus T.', 'Houston, USA', 5, 'She said yes. The flowers, the photographer, the timing with the light, everything was handled.'],
                ['jet-skiing', 'Andre B.', 'Chaguanas', 4, 'Quick to book on WhatsApp, new skis, no fuss. Would have liked a longer slot but the price is fair.'],
                ['beach-massages', 'Lauren K.', 'Manchester, UK', 5, 'A massage on the sand at Pigeon Point with the sea behind me. I booked a second one two days later.'],
            ];
            foreach ($reviews as [$slug, $name, $origin, $rating, $body]) {
                Review::create(['experience_id' => Experience::where('slug', $slug)->value('id'), 'name' => $name, 'origin' => $origin, 'rating' => $rating, 'body' => $body, 'approved' => true, 'featured' => $rating === 5]);
            }
        }

        if (Booking::count() === 0) {
            $experiences = Experience::all();
            foreach (range(1, 18) as $i) {
                $exp = $experiences->random();
                $adults = rand(1, 4);
                $children = rand(0, 2);
                $date = now()->addDays(rand(-20, 25));
                $status = $date->isPast() ? (rand(0, 5) ? 'completed' : 'cancelled') : collect(['new', 'new', 'confirmed', 'paid'])->random();
                $b = Booking::create([
                    'experience_id' => $exp->id, 'date' => $date, 'time_slot' => collect(array_keys(Booking::SLOTS))->random(),
                    'adults' => $adults, 'children' => $children, 'name' => fake()->name(), 'email' => fake()->safeEmail(), 'phone' => '+1 868 '.rand(200, 799).'-'.rand(1000, 9999),
                    'contact_via' => 'whatsapp', 'pickup' => collect(config('site.pickup_areas'))->random(), 'notes' => rand(0, 1) ? fake()->sentence(10) : null,
                    'estimate' => $exp->estimate($adults, $children), 'status' => $status, 'source' => ['utm_source' => collect(['instagram', 'instagram', 'google', null])->random(), 'device' => 'mobile'],
                    'created_at' => $date->copy()->subDays(rand(2, 12)), 'confirmed_at' => in_array($status, ['confirmed', 'paid', 'completed']) ? $date->copy()->subDays(1) : null,
                ]);
                $b->activities()->create(['type' => 'created', 'body' => 'Request received from the website.', 'created_at' => $b->created_at]);
            }
        }
    }
}
