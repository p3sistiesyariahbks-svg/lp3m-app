<?php

namespace Database\Factories;

use App\Enums\StatusType;
use App\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Partner>
 */
class PartnerFactory extends Factory
{
    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Partner $partner) {
            $partner->addMediaFromUrl('https://picsum.photos/200/300')
                ->toMediaCollection('logos', 'public');
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'url' => fake()->url(),
            'status' => fake()->randomElement(StatusType::class),
            'published_at' => null,
        ];
    }

    /**
     * Indicate that the page is draft.
     */
    public function draft(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => StatusType::Draft,
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the page is published.
     */
    public function published(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => StatusType::Published,
            'published_at' => now(),
        ]);
    }

    /**
     * Indicate that the page is scheduled.
     */
    public function scheduled(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => StatusType::Scheduled,
            'published_at' => fake()->dateTimeBetween('now', '+1 week'),
        ]);
    }
}
