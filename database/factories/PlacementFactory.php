<?php

namespace Database\Factories;

use App\Models\Location;
use App\Models\Period;
use App\Models\Placement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Placement>
 */
class PlacementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'period_id' => Period::factory(),
            'location_id' => Location::factory(),
            'supervisor_id' => User::factory(),
            'quota' => fake()->randomDigitNotZero(),
            'filled' => 0,
        ];
    }
}
