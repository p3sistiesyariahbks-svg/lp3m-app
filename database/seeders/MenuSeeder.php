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
                ['title' => 'Home', 'url' => '/', 'order' => 0],
                ['title' => 'News', 'url' => '/news', 'order' => 3],
                ['title' => 'Document', 'url' => '/documents', 'order' => 4],
                ['title' => 'Contact', 'url' => '/contact', 'order' => 6],
            ))
            ->create();

        MenuItem::factory()
            ->count(1)
            ->customText()
            ->for($header)
            ->state([
                'title' => 'Profile',
                'order' => 1,
            ])
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

        MenuItem::factory()
            ->customText()
            ->for($header)
            ->state([
                'title' => 'Gallery',
                'order' => 5,
            ])
            ->has(
                MenuItem::factory()
                    ->count(2)
                    ->staticMenu()
                    ->for($header)
                    ->state(new Sequence(
                        ['title' => 'Photo', 'url' => '/gallery/photos', 'order' => 0],
                        ['title' => 'Video', 'url' => '/gallery/videos', 'order' => 1],
                    )),
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
