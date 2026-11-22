<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\StudyProgram;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::factory()
            ->count(4)
            ->active()
            ->has(
                StudyProgram::factory()
                    ->count(3)
                    ->active(),
                'studyPrograms',
            )
            ->create();

        Department::factory()
            ->count(1)
            ->inactive()
            ->has(
                StudyProgram::factory()
                    ->count(2)
                    ->inactive(),
                'studyPrograms',
            )
            ->create();
    }
}
