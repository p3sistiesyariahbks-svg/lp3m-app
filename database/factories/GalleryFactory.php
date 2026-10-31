<?php

namespace Database\Factories;

use App\Enums\GalleryType;
use App\Enums\StatusType;
use App\Enums\TaxonomyType;
use App\Models\Gallery;
use App\Models\Taxonomy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use JordJD\PHPSummary\SummaryTool;
use Soundasleep\Html2Text;

/**
 * @extends Factory<Gallery>
 */
class GalleryFactory extends Factory
{
    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (Gallery $gallery) {
            $text = Html2Text::convert($gallery->content, [
                'ignore_errors' => true,
                'drop_links' => true,
                'char_set' => 'auto',
            ]);

            $gallery->description = Str::limit(
                (new SummaryTool($text))->getSummary(),
                160
            );
        })->afterCreating(function (Gallery $gallery) {
            $taxonomyIds = Taxonomy::where('type', TaxonomyType::Gallery)
                ->inRandomOrder()
                ->limit(3)
                ->pluck('id')
                ->all();
            $gallery->attachTaxonomies($taxonomyIds);

            $gallery->addMediaFromUrl('https://picsum.photos/200/300')
                ->toMediaCollection('thumbnails', 'public');
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
            'type' => fake()->randomElement(GalleryType::class),
            'title' => fake()->sentence(),
            'slug' => function (array $attributes): string {
                return Str::slug($attributes['title']);
            },
            'content' => fake()->randomHtml(),
            'video_url' => 'https://www.youtube.com/embed/tgbNymZ7vqY',
            'status' => fake()->randomElement(StatusType::class),
            'published_at' => null,
        ];
    }

    /**
     * Indicate that the gallery is a photo.
     */
    public function photo(): Factory
    {
        return $this->state(fn (array $attributes): array => [
            'type' => GalleryType::Photos,
            'video_url' => null,
        ]);
    }

    /**
     * Indicate that the gallery is a video.
     */
    public function video(): Factory
    {
        return $this->state(fn (array $attributes): array => [
            'type' => GalleryType::Videos,
            'video_url' => 'https://www.youtube.com/embed/tgbNymZ7vqY',
        ]);
    }

    /**
     * Indicate that the gallery is draft.
     */
    public function draft(): Factory
    {
        return $this->state(fn (array $attributes): array => [
            'status' => StatusType::Draft,
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the gallery is published.
     */
    public function published(): Factory
    {
        return $this->state(fn (array $attributes): array => [
            'status' => StatusType::Published,
            'published_at' => now(),
        ]);
    }

    /**
     * Indicate that the gallery is scheduled.
     */
    public function scheduled(): Factory
    {
        return $this->state(fn (array $attributes): array => [
            'status' => StatusType::Scheduled,
            'published_at' => fake()->dateTimeBetween('now', '+1 week'),
        ]);
    }
}
