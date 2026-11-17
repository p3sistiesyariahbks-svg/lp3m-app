<?php

namespace App\Models;

use App\Enums\StatusType;
use Closure;
use Cviebrock\EloquentSluggable\Sluggable;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Database\Factories\AnnouncementFactory;
use Filament\Forms\Components\RichEditor\Models\Concerns\InteractsWithRichContent;
use Filament\Forms\Components\RichEditor\Models\Contracts\HasRichContent;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Kisame76\FilamentAdvancedRichEditor\RichEditor\Plugins\SpatieMediaLibraryPlugin;
use Mattiverse\Userstamps\Traits\Userstamps;
use Mews\Purifier\Casts\CleanHtml;
use Rankbeam\Seo\Traits\HasSEO;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Announcement extends Model implements HasMedia, HasRichContent, Viewable
{
    /** @use HasFactory<AnnouncementFactory> */
    use HasFactory, HasSEO, HasUuids, InteractsWithMedia, InteractsWithRichContent, InteractsWithViews, Sluggable, Userstamps;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'announcements';

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
     * Get the announcement's slug.
     */
    protected function slugSource(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->slug ?: $this->title,
        );
    }

    /**
     * Get the sluggable configuration for the model.
     *
     * @return array<string, array<string, bool|string|Closure>>
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
     * Get the canonical URL of the announcement for SEO.
     */
    public function getUrlForSEO(): string
    {
        return route('announcements.show', $this);
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
}
