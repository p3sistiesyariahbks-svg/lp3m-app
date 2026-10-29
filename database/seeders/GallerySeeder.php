<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\GalleryPhoto;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Gallery::factory()
            ->count(12)
            ->photo()
            ->draft()
            ->has(
                GalleryPhoto::factory()
                    ->count(5),
                'photos'
            )
            ->create();

        Gallery::factory()
            ->count(12)
            ->photo()
            ->published()
            ->has(
                GalleryPhoto::factory()
                    ->count(5),
                'photos'
            )
            ->create();

        Gallery::factory()
            ->count(12)
            ->photo()
            ->scheduled()
            ->has(
                GalleryPhoto::factory()
                    ->count(5),
                'photos'
            )
            ->create();

        Gallery::factory()
            ->count(12)
            ->video()
            ->draft()
            ->create();

        Gallery::factory()
            ->count(12)
            ->video()
            ->published()
            ->create();

        Gallery::factory()
            ->count(12)
            ->video()
            ->scheduled()
            ->create();
    }
}
