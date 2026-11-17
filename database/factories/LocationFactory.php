<?php

namespace Database\Factories;

use App\Enums\RegionLevel;
use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Location>
 */
class LocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'level' => fake()->randomElement(RegionLevel::class),
            'content' => fake()
                ->filamentRichContent()
                ->heading()
                ->paragraphs(
                    count: 3,
                    links: true,
                    bold: true,
                    italic: true,
                )
                ->bulletList()
                ->toHtml(),
            'lat' => fake()->latitude(),
            'lng' => fake()->longitude(),
            'address' => fake()->address(),
            'is_active' => fake()->boolean(),
        ];
    }
}
