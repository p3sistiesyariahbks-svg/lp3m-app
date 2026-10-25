<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuLocation;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $header = Menu::factory()
            ->create();

        MenuLocation::factory()
            ->header()
            ->for($header)
            ->create();

        MenuItem::factory()
            ->count(4)
            ->staticMenu()
            ->for($header)
            ->state(new Sequence(
                ['title' => 'Home', 'url' => '/'],
                ['title' => 'News', 'url' => '/news'],
                ['title' => 'Document', 'url' => '/documents'],
                ['title' => 'Contact', 'url' => '/contact'],
            ))
            ->create();

        MenuItem::factory()
            ->count(1)
            ->customText()
            ->for($header)
            ->has(
                MenuItem::factory()
                    ->count(10)
                    ->customText()
                    ->for($header)
                    ->has(
                        MenuItem::factory()
                            ->count(5)
                            ->for($header)
                            ->page(),
                        'children',
                    ),
                'children',
            )
            ->create();

        $footer = Menu::factory()
            ->create();

        MenuLocation::factory()
            ->footer()
            ->for($footer)
            ->create();

        MenuItem::factory()
            ->count(4)
            ->customText()
            ->for($footer)
            ->has(
                MenuItem::factory()
                    ->count(5)
                    ->customLink()
                    ->for($footer),
                'children',
            )
            ->create();
    }
}
