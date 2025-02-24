<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use App\Enums\UserRoleEnum;
use Illuminate\Database\Seeder;
use App\Models\Animal\Sponsorship;
use Illuminate\Support\Facades\Hash;
use Database\Seeders\SponsorshipSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'ale@ale.com',
            'password' => Hash::make('123456'),
            'role' => UserRoleEnum::Admin,
        ]);

        $this->call(UserSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(AnimalSeeder::class);
        $this->call(ExpenseSeeder::class);
        $this->call(SponsorshipSeeder::class);
    }
}
