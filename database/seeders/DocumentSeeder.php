<?php

namespace Database\Seeders;

use App\Models\Document;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Document::factory()
            ->count(60)
            ->draft()
            ->create();

        Document::factory()
            ->count(60)
            ->published()
            ->create();

        Document::factory()
            ->count(60)
            ->scheduled()
            ->create();
    }
}
