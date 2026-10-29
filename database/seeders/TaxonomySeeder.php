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
            ->documents()
            ->has(
                Taxonomy::factory()
                    ->count(2)
                    ->documents()
                    ->has(
                        Taxonomy::factory()
                            ->count(2)
                            ->documents(),
                        'children',
                    ),
                'children',
            )
            ->create();

        Taxonomy::factory()
            ->count(1)
            ->gallery()
            ->has(
                Taxonomy::factory()
                    ->count(2)
                    ->gallery()
                    ->has(
                        Taxonomy::factory()
                            ->count(2)
                            ->gallery(),
                        'children',
                    ),
                'children',
            )
            ->create();
    }
}
