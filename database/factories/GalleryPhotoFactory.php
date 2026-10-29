<?php

namespace Database\Factories;

use App\Models\Gallery;
use App\Models\GalleryPhoto;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<GalleryPhoto>
 */
class GalleryPhotoFactory extends Factory
{
    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterCreating(function (GalleryPhoto $photo) {
            $photo->addMediaFromUrl('https://picsum.photos/200/300')
                ->toMediaCollection('images', 'public');
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
            'gallery_id' => Gallery::factory()->photo(),
            'alt' => fake()->sentence(3),
            'sort_order' => 0,
        ];
    }
}
