<?php

namespace Database\Factories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Menu>
 */
class MenuFactory extends Factory
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
            'is_visible' => true,
        ];
    }

    /**
     * Indicate that the menu is hidden.
     */
    public function hidden(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'is_visible' => false,
        ]);
    }
}
