<?php

namespace Database\Seeders;

use App\Models\News;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        News::factory()
            ->count(12)
            ->draft()
            ->create();

        News::factory()
            ->count(12)
            ->published()
            ->create();

        News::factory()
            ->count(12)
            ->scheduled()
            ->create();
    }
}
