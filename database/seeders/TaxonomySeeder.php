<?php

namespace Database\Seeders;

use App\Models\Taxonomy;
use Illuminate\Database\Seeder;

class TaxonomySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Taxonomy::factory()
            ->count(1)
            ->news()
            ->has(
                Taxonomy::factory()
                    ->count(2)
                    ->news()
                    ->has(
                        Taxonomy::factory()
                            ->count(2)
                            ->news(),
                        'children',
                    ),
                'children',
            )
            ->create();

        Taxonomy::factory()
            ->count(1)
            ->document()
            ->has(
                Taxonomy::factory()
                    ->count(2)
                    ->document()
                    ->has(
                        Taxonomy::factory()
                            ->count(2)
                            ->document(),
                        'children',
                    ),
                'children',
            )
            ->create();
    }
}
