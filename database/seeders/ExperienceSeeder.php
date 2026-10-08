<?php

namespace Database\Seeders;

use App\Models\Experience;
use Illuminate\Database\Seeder;

/** Loads the catalogue from config/experiences.php. Safe to re-run: updates by slug, never deletes. */
class ExperienceSeeder extends Seeder
{
    public function run(): void
    {
        foreach (config('experiences.items') as $item) {
            Experience::updateOrCreate(['slug' => $item['slug']], $item);
        }
    }
}
