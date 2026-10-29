<?php

namespace App\Models;

use Aliziodev\LaravelTaxonomy\Models\Taxonomy as BaseTaxonomy;
use Database\Factories\TaxonomyFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Taxonomy extends BaseTaxonomy
{
    /** @use HasFactory<TaxonomyFactory> */
    use HasFactory, HasUuids;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'taxonomies';

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
     * Get the parent of this taxonomy.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id', 'id');
    }

    /**
     * Get the children of this taxonomy.
     */
    public function children(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id', 'id');
    }

    /**
     * Get all of the news that are assigned this taxonomy.
     */
    public function news(): MorphToMany
    {
        return $this->morphedByMany(News::class, 'taxonomable', 'taxonomables');
    }

    /**
     * Get all of the documents that are assigned this taxonomy.
     */
    public function documents(): MorphToMany
    {
        return $this->morphedByMany(Document::class, 'taxonomable', 'taxonomables');
    }

    /**
     * Get all of the galleries that are assigned this taxonomy.
     */
    public function galleries(): MorphToMany
    {
        return $this->morphedByMany(Gallery::class, 'taxonomable', 'taxonomables');
    }
}
