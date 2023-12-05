<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $this->call([
            DepartmentSeeder::class,
            TitleSeeder::class,
            ShiftSeeder::class,
            UserSeeder::class,
            CompanySeeder::class,
            CarBrandSeeder::class,
            CarTypeSeeder::class,
        ]);
    }
}
