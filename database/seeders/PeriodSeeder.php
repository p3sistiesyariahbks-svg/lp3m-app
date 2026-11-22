<?php

namespace Database\Seeders;

use App\Models\Guardian;
use App\Models\Period;
use App\Models\Placement;
use App\Models\Registration;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Database\Seeder;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Period::factory()
            ->count(2)
            ->draft()
            ->has(
                Placement::factory()
                    ->count(4)
                    ->for(User::factory(), 'supervisor'),
                'placements',
            )
            ->create();

        Period::factory()
            ->count(2)
            ->published()
            ->has(
                Placement::factory()
                    ->count(4)
                    ->for(User::factory(), 'supervisor')
                    ->has(
                        Registration::factory()
                            ->draft(),
                        'registrations',
                    )
                    ->has(
                        Registration::factory()
                            ->submitted()
                            ->state(fn (array $attributes): array => [
                                'user_id' => User::factory()
                                    ->has(
                                        StudentProfile::factory()
                                            ->state(fn (array $attributes, User $user): array => [
                                                'name' => $user->name,
                                            ]),
                                        'studentProfile',
                                    )
                                    ->has(
                                        Guardian::factory()
                                            ->father(),
                                        'guardians',
                                    )
                                    ->has(
                                        Guardian::factory()
                                            ->mother(),
                                        'guardians',
                                    ),
                            ]),
                        'registrations',
                    )
                    ->has(
                        Registration::factory()
                            ->approved()
                            ->state(fn (array $attributes): array => [
                                'user_id' => User::factory()
                                    ->has(
                                        StudentProfile::factory()
                                            ->state(fn (array $attributes, User $user): array => [
                                                'name' => $user->name,
                                            ]),
                                        'studentProfile',
                                    )
                                    ->has(
                                        Guardian::factory()
                                            ->father(),
                                        'guardians',
                                    )
                                    ->has(
                                        Guardian::factory()
                                            ->mother(),
                                        'guardians',
                                    ),
                            ]),
                        'registrations',
                    )
                    ->has(
                        Registration::factory()
                            ->rejected()
                            ->state(fn (array $attributes): array => [
                                'user_id' => User::factory()
                                    ->has(
                                        StudentProfile::factory()
                                            ->state(fn (array $attributes, User $user): array => [
                                                'name' => $user->name,
                                            ]),
                                        'studentProfile',
                                    )
                                    ->has(
                                        Guardian::factory()
                                            ->father(),
                                        'guardians',
                                    )
                                    ->has(
                                        Guardian::factory()
                                            ->mother(),
                                        'guardians',
                                    ),
                            ]),
                        'registrations',
                    ),
                'placements',
            )
            ->create();

        Period::factory()
            ->count(2)
            ->scheduled()
            ->has(
                Placement::factory()
                    ->count(4)
                    ->for(User::factory(), 'supervisor'),
                'placements',
            )
            ->create();
    }
}
