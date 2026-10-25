<?php

namespace Database\Factories;

use App\Enums\TaxonomyType;
use App\Models\Taxonomy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Taxonomy>
 */
class TaxonomyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->word(),
            'slug' => function (array $attributes) {
                return Str::slug($attributes['name']);
            },
            'description' => fake()->sentence(),
            'type' => fake()->randomElement(TaxonomyType::class),
        ];
    }

    /**
     * Indicate that the taxonomy is news.
     */
    public function news(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TaxonomyType::News,
        ]);
    }

    /**
     * Indicate that the taxonomy is document.
     */
    public function document(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => TaxonomyType::Documents,
        ]);
    }
}
