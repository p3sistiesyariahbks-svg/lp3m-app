<?php

namespace App\Models;

use App\Enums\RegionLevel;
use Database\Factories\LocationFactory;
use EduardoRibeiroDev\FilamentLeaflet\ValueObjects\Coordinate;
use Filament\Forms\Components\RichEditor\Models\Concerns\InteractsWithRichContent;
use Filament\Forms\Components\RichEditor\Models\Contracts\HasRichContent;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Kisame76\FilamentAdvancedRichEditor\RichEditor\Plugins\SpatieMediaLibraryPlugin;
use Mattiverse\Userstamps\Traits\Userstamps;
use Mews\Purifier\Casts\CleanHtml;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Location extends Model implements HasMedia, HasRichContent
{
    /** @use HasFactory<LocationFactory> */
    use HasFactory, HasUuids, InteractsWithMedia, InteractsWithRichContent, Userstamps;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'locations';

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
        'name',
        'level',
        'content',
        'lat',
        'lng',
        'address',
        'is_active',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'level' => RegionLevel::class,
            'content' => CleanHtml::class,
            'lat' => 'float',
            'lng' => 'float',
            'coordinates' => Coordinate::class,
            'is_active' => 'boolean',
        ];
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
     * Get all of the placements for the locations.
     */
    public function placements(): HasMany
    {
        return $this->hasMany(Placement::class);
    }
}
