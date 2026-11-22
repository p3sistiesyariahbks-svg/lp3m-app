<?php

namespace Database\Factories;

use App\Enums\EducationLevel;
use App\Enums\GuardianRelation;
use App\Models\Guardian;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Guardian>
 */
class GuardianFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'relation' => fake()->randomElement(GuardianRelation::class),
            'name' => fake()->name(),
            'birth_place' => fake()->city(),
            'birth_date' => fake()->dateTimeBetween('-60 years', '-40 years'),
            'education' => fake()->randomElement(EducationLevel::class),
            'occupation' => fake()->jobTitle(),
            'address' => fake()->address(),
        ];
    }

    /**
     * Indicate that the guardian is the father.
     */
    public function father(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'relation' => GuardianRelation::Father,
        ]);
    }

    /**
     * Indicate that the guardian is the mother.
     */
    public function mother(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'relation' => GuardianRelation::Mother,
        ]);
    }
}
