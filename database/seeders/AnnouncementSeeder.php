<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Announcement::factory()
            ->count(10)
            ->draft()
            ->create();

        Announcement::factory()
            ->count(10)
            ->published()
            ->create();

        Announcement::factory()
            ->count(10)
            ->scheduled()
            ->create();
    }
}
