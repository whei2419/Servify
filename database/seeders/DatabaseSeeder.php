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
        $this->call(TimeframeSeeder::class);
        $this->call(DocumentSeeder::class);
        $this->call(InputSeeder::class);
        $this->call(DocumentInputSeeder::class);
        $this->call(StatusSeeder::class);
    }
}
