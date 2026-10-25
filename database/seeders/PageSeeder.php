<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::factory()
            ->count(10)
            ->draft()
            ->create();

        Page::factory()
            ->count(10)
            ->published()
            ->create();

        Page::factory()
            ->count(10)
            ->scheduled()
            ->create();
    }
}
