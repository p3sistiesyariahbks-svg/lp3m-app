<?php

namespace Database\Factories;

use App\Enums\DegreeLevel;
use App\Models\Department;
use App\Models\StudyProgram;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<StudyProgram>
 */
class StudyProgramFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'department_id' => Department::factory(),
            'name' => fake()->words(4, true),
            'code' => function (array $attributes) {
                return Str::of($attributes['name'])
                    ->acronym()
                    ->upper()
                    ->toString();
            },
            'degree' => fake()->randomElement(DegreeLevel::class),
            'is_active' => fake()->boolean(),
        ];
    }

    /**
     * Indicate that the study program is active.
     */
    public function active(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the study program is inactive.
     */
    public function inactive(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}
