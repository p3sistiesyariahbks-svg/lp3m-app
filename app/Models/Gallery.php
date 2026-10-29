<?php

namespace App\Models;

use Aliziodev\LaravelTaxonomy\Traits\HasTaxonomy;
use App\Enums\GalleryType;
use App\Enums\SortByType;
use App\Enums\StatusType;
use App\Enums\TaxonomyType;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Database\Factories\GalleryFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\Vite;
use Mattiverse\Userstamps\Traits\Userstamps;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Gallery extends Model implements HasMedia, Viewable
{
    /** @use HasFactory<GalleryFactory> */
    use HasFactory, HasTaxonomy, HasUuids, InteractsWithMedia, InteractsWithViews, Userstamps;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'galleries';

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
        'type',
        'description',
        'video_url',
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
            'type' => GalleryType::class,
            'published_at' => 'datetime',
            'status' => StatusType::class,
        ];
    }

    /**
     * Scope a query to only include gallery of a sort by type.
     */
    #[Scope]
    protected function sortBy(Builder $query, SortByType $sortBy): void
    {
        match ($sortBy) {
            SortByType::Latest => $query->latest('galleries.published_at'),
            SortByType::Oldest => $query->oldest('galleries.published_at'),
            SortByType::TitleAsc => $query->orderBy('galleries.title'),
            SortByType::TitleDesc => $query->orderByDesc('galleries.title'),
            SortByType::Popular => $query->withCount('views')->orderByDesc('views_count'),
        };
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
     * Get all of the photos for the gallery.
     */
    public function photos(): HasMany
    {
        return $this->hasMany(GalleryPhoto::class)->orderBy('sort_order');
    }

    /**
     * Get all of the categories for the gallery.
     */
    public function categories(): MorphToMany
    {
        return $this->taxonomies()->where('type', TaxonomyType::Gallery);
    }
}
