<?php

namespace Database\Factories;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\Page;
use Datlechin\FilamentMenuBuilder\Enums\LinkTarget;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MenuItem>
 */
class MenuItemFactory extends Factory
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
            'parent_id' => null,
            'linkable_type' => null,
            'linkable_id' => null,
            'panel' => null,
            'title' => fake()->word(),
            'url' => fake()->url(),
            'icon' => null,
            'classes' => null,
            'target' => fake()->randomElement(LinkTarget::class),
            'order' => fake()->randomDigitNotNull(),
        ];
    }

    /**
     * Indicate that the menu item is page.
     */
    public function page(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'linkable_type' => Page::class,
            'linkable_id' => Page::factory()->published(),
            'panel' => Page::class,
            'title' => function (array $attributes) {
                return Page::find($attributes['linkable_id'])->title;
            },
            'url' => null,
        ]);
    }

    /**
     * Indicate that the menu item is static menu.
     */
    public function staticMenu(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'panel' => 'static-menu',
        ]);
    }

    /**
     * Indicate that the menu item is custom link.
     */
    public function customLink(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'icon' => 'heroicon-o-home',
            'classes' => 'text-sm font-bold',
            'rel' => 'nofollow noopener noreferrer',
        ]);
    }

    /**
     * Indicate that the menu item is custom text.
     */
    public function customText(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'url' => null,
        ]);
    }

    /**
     * Indicate that the menu item is a child of the given item.
     */
    public function childOf(MenuItem $menuItem): Factory
    {
        return $this->state(fn (array $attributes) => [
            'parent_id' => $menuItem->getKey(),
        ]);
    }
}
