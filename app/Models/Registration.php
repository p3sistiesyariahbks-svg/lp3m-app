<?php

namespace App\Models;

use App\Enums\GuardianRelation;
use App\Enums\RegistrationStatus;
use Database\Factories\RegistrationFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Mattiverse\Userstamps\Traits\Userstamps;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Registration extends Model implements HasMedia
{
    /** @use HasFactory<RegistrationFactory> */
    use HasFactory, HasUuids, InteractsWithMedia, Userstamps;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'registrations';

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
        'user_id',
        'period_id',
        'placement_id',
        'status',
        'amount',
        'submitted_at',
        'note',
        'reviewed_by',
        'reviewed_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => RegistrationStatus::class,
            'amount' => 'integer',
            'submitted_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    /**
     * Register the media collections.
     */
    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('computer-certificate')
            ->useDisk('local');
        $this
            ->addMediaCollection('skpi-certificate')
            ->useDisk('local');
        $this
            ->addMediaCollection('parental-consent-letter')
            ->useDisk('local');
        $this
            ->addMediaCollection('id-card')
            ->useDisk('local');
        $this
            ->addMediaCollection('receipt')
            ->useDisk('local');
    }

    /**
     * Get the user that owns the registration.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the period that owns the registration.
     */
    public function period(): BelongsTo
    {
        return $this->belongsTo(Period::class);
    }

    /**
     * Get the placement that owns the registration.
     */
    public function placement(): BelongsTo
    {
        return $this->belongsTo(Placement::class);
    }

    /**
     * Get the student profile associated with the registration.
     */
    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class, 'user_id', 'user_id');
    }

    /**
     * Get the father associated with the registration.
     */
    public function father(): HasOne
    {
        return $this->hasOne(Guardian::class, 'user_id', 'user_id')
            ->withAttributes(['relation' => GuardianRelation::Father]);
    }

    /**
     * Get the mother associated with the registration.
     */
    public function mother(): HasOne
    {
        return $this->hasOne(Guardian::class, 'user_id', 'user_id')
            ->withAttributes(['relation' => GuardianRelation::Mother]);
    }

    /**
     * Get the user that reviewed the registration.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
