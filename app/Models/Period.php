<?php

namespace App\Models;

use App\Enums\ProgramType;
use App\Enums\StatusType;
use Database\Factories\PeriodFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mattiverse\Userstamps\Traits\Userstamps;

class Period extends Model
{
    /** @use HasFactory<PeriodFactory> */
    use HasFactory, HasUuids, Userstamps;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'periods';

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
        'program',
        'program_starts_at',
        'program_ends_at',
        'registration_opens_at',
        'registration_closes_at',
        'fee',
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
            'program' => ProgramType::class,
            'program_starts_at' => 'date',
            'program_ends_at' => 'date',
            'registration_opens_at' => 'date',
            'registration_closes_at' => 'date',
            'published_at' => 'datetime',
            'status' => StatusType::class,
        ];
    }

    /**
     * Get all of the placements for the periods.
     */
    public function placements(): HasMany
    {
        return $this->hasMany(Placement::class);
    }

    /**
     * Get all of the registrations for the periods.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
}
