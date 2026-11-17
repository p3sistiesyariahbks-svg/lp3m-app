<?php

namespace Database\Factories;

use App\Enums\ProgramType;
use App\Enums\StatusType;
use App\Models\Period;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Period>
 */
class PeriodFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->sentence(),
            'program' => fake()->randomElement(ProgramType::class),
            'program_starts_at' => fake()->dateTimeBetween('+3 months', '+4 months'),
            'program_ends_at' => function (array $attributes) {
                return fake()->dateTimeBetween(
                    $attributes['program_starts_at'],
                    '+6 months',
                );
            },
            'registration_opens_at' => fake()->dateTimeBetween('now', '+1 week'),
            'registration_closes_at' => function (array $attributes) {
                return fake()->dateTimeBetween(
                    $attributes['registration_opens_at'],
                    $attributes['program_starts_at'],
                );
            },
            'fee' => fake()->numberBetween(100_000, 2_000_000),
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
