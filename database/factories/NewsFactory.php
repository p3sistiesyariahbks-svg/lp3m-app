<?php

namespace Database\Factories;

use App\Enums\StatusType;
use App\Enums\TaxonomyType;
use App\Models\News;
use App\Models\Page;
use App\Models\Taxonomy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use JordJD\PHPSummary\SummaryTool;
use Mtownsend\ReadTime\ReadTime;
use Soundasleep\Html2Text;

/**
 * @extends Factory<News>
 */
class NewsFactory extends Factory
{
    /**
     * Configure the model factory.
     */
    public function configure(): static
    {
        return $this->afterMaking(function (News $news) {
            $text = Html2Text::convert($news->content, [
                'ignore_errors' => true,
                'drop_links' => true,
                'char_set' => 'auto',
            ]);

            $news->description = Str::limit(
                (new SummaryTool($text))->getSummary(),
                160
            );
            $news->reading_time = (new ReadTime($text))
                ->abbreviated()
                ->omitSeconds()
                ->wpm(230)
                ->get();
        })->afterCreating(function (News $news) {
            $taxonomyIds = Taxonomy::where('type', TaxonomyType::News)
                ->inRandomOrder()
                ->limit(3)
                ->pluck('id')
                ->all();
            $news->attachTaxonomies($taxonomyIds);

            $news->addMediaFromUrl('https://picsum.photos/200/300')
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
            'title' => fake()->sentence(),
            'slug' => function (array $attributes) {
                return Str::slug($attributes['title']);
            },
            'content' => fake()->randomHtml(),
            'status' => fake()->randomElement(StatusType::class),
            'published_at' => null,
        ];
    }

    /**
     * Indicate that the page is draft.
     */
    public function draft(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => StatusType::Draft,
            'published_at' => null,
        ]);
    }

    /**
     * Indicate that the page is published.
     */
    public function published(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => StatusType::Published,
            'published_at' => now(),
        ]);
    }

    /**
     * Indicate that the page is scheduled.
     */
    public function scheduled(): Factory
    {
        return $this->state(fn (array $attributes) => [
            'status' => StatusType::Scheduled,
            'published_at' => fake()->dateTimeBetween('now', '+1 week'),
        ]);
    }
}
