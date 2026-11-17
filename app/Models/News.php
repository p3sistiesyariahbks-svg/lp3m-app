<?php

namespace App\Models;

use Aliziodev\LaravelTaxonomy\Traits\HasTaxonomy;
use App\Enums\SortByType;
use App\Enums\StatusType;
use App\Enums\TaxonomyType;
use App\Observers\NewsObserver;
use Closure;
use Cviebrock\EloquentSluggable\Sluggable;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Database\Factories\NewsFactory;
use Filament\Forms\Components\RichEditor\Models\Concerns\InteractsWithRichContent;
use Filament\Forms\Components\RichEditor\Models\Contracts\HasRichContent;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Str;
use Kisame76\FilamentAdvancedRichEditor\RichEditor\Plugins\SpatieMediaLibraryPlugin;
use Mattiverse\Userstamps\Traits\Userstamps;
use Mews\Purifier\Casts\CleanHtml;
use Rankbeam\Seo\Traits\HasSEO;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[ObservedBy([NewsObserver::class])]
class News extends Model implements HasMedia, HasRichContent, Viewable
{
    /** @use HasFactory<NewsFactory> */
    use HasFactory, HasSEO, HasTaxonomy, HasUuids, InteractsWithMedia, InteractsWithRichContent, InteractsWithViews, Sluggable, Userstamps;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'news';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'status',
        'published_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'content' => CleanHtml::class,
            'published_at' => 'datetime',
            'status' => StatusType::class,
        ];
    }

    /**
     * Get the news's slug.
     */
    protected function slugSource(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->slug ?: $this->title,
        );
    }

    /**
     * Scope a query to only include news of a given type.
     */
    #[Scope]
    protected function sortBy(Builder $query, SortByType $sortBy): void
    {
        match ($sortBy) {
            SortByType::Latest => $query->latest('news.published_at'),
            SortByType::Oldest => $query->oldest('news.published_at'),
            SortByType::TitleAsc => $query->orderBy('news.title'),
            SortByType::TitleDesc => $query->orderByDesc('news.title'),
            SortByType::Popular => $query->withCount('views')->orderByDesc('views_count'),
        };
    }

    /**
     * Get the sluggable configuration for the model.
     *
     * @return array<string, array<string, string|Closure>>
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'slug_source',
                'onUpdate' => true,
                'uniqueSuffix' => static fn (
                    string $slug,
                    string $separator,
                    Collection $list,
                    int $firstSuffix
                ): string => Str::lower(Str::random(6)),
            ],
        ];
    }

    /**
     * Get the canonical URL of the news for SEO.
     */
    public function getUrlForSEO(): string
    {
        return route('news.show', $this);
    }

    /**
     * Get the image of the news for SEO.
     */
    public function getSEOImage(): ?string
    {
        return $this->getFirstMediaUrl('thumbnails');
    }

    /**
     * Register the media collections for this model.
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('thumbnails')
            ->useFallbackUrl(Vite::asset('/images/placeholder.webp'))
            ->useFallbackPath(public_path('/images/placeholder.webp'));
    }

    /**
     * Setup rich editor content.
     */
    public function setUpRichContent(): void
    {
        $this->registerRichContent('content')
            ->plugins([
                SpatieMediaLibraryPlugin::make('rich-editor'),
            ]);
    }

    /**
     * Get all of the categories for the news.
     */
    public function categories(): MorphToMany
    {
        return $this->taxonomies()->where('type', TaxonomyType::News);
    }
}
