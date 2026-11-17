<?php

namespace Database\Factories;

use App\Models\Placement;
use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Registration>
 */
class RegistrationFactory extends Factory
{
    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (Registration $registration) {
            $registration->placement?->increment('filled');
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
            'user_id' => User::factory(),
            'placement_id' => Placement::factory(),
            'period_id' => function (array $attributes): string {
                return Placement::find($attributes['placement_id'])->period_id;
            },
        ];
    }
}
