<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Enums\LivingWith;
use App\Enums\Religion;
use App\Models\StudentProfile;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudentProfile>
 */
class StudentProfileFactory extends Factory
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
            'study_program_id' => StudyProgram::factory(),
            'name' => fake()->name(),
            'nik' => fake()->unique()->numerify('################'),
            'nim' => fake()->unique()->numerify('##########'),
            'gender' => fake()->randomElement(Gender::class),
            'birth_place' => fake()->city(),
            'birth_date' => fake()->dateTimeBetween('-25 years', '-18 years'),
            'religion' => fake()->randomElement(Religion::class),
            'address' => fake()->address(),
            'living_with' => fake()->randomElement(LivingWith::class),
            'living_with_other' => fake()->sentence(3),
        ];
    }
}
