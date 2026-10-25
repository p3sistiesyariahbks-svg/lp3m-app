<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Partner::factory()
            ->count(10)
            ->draft()
            ->create();

        Partner::factory()
            ->count(10)
            ->published()
            ->create();

        Partner::factory()
            ->count(10)
            ->scheduled()
            ->create();
    }
}
