<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\MenuLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MenuLocation>
 */
class MenuLocationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'menu_id' => Menu::factory(),
            'location' => function (array $attributes) {
                return Menu::find($attributes['menu_id'])->name;
            },
        ];
    }

    /**
     * Indicate that the menu location is header.
     */
    public function header(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'location' => 'header',
        ]);
    }

    /**
     * Indicate that the menu location is footer.
     */
    public function footer(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'location' => 'footer',
        ]);
    }
}
