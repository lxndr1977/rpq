<?php

namespace Database\Seeders;

use App\Models\Animal\Location;
use Illuminate\Database\Seeder;
use Database\Factories\Animal\LocationFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::factory(10)->create();
    }
}
