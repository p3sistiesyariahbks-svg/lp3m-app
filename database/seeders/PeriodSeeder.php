<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Period;
use App\Models\Placement;
use App\Models\Registration;
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
                            ->has(
                                Payment::factory()
                                    ->pending(),
                                'payments'
                            ),
                        'registrations',
                    )
                    ->has(
                        Registration::factory()
                            ->has(
                                Payment::factory()
                                    ->verified(),
                                'payments'
                            ),
                        'registrations',
                    )
                    ->has(
                        Registration::factory()
                            ->has(
                                Payment::factory()
                                    ->rejected(),
                                'payments'
                            ),
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
