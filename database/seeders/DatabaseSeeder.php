<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanyEmployee;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory()->create();
        // Company::factory()->create();
        CompanyEmployee::factory()->create();
    }
}
