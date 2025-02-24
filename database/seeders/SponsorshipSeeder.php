<?php

namespace Database\Seeders;

use App\Models\Animal\Sponsorship;
use Illuminate\Database\Seeder;

class SponsorshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Sponsorship::factory(100)->create();
    }
}
