<?php

namespace App\Models;

use App\Enums\EducationLevel;
use App\Enums\GuardianRelation;
use Database\Factories\GuardianFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mattiverse\Userstamps\Traits\Userstamps;

class Guardian extends Model
{
    /** @use HasFactory<GuardianFactory> */
    use HasFactory, HasUuids, Userstamps;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'guardians';

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
        'relation',
        'name',
        'birth_place',
        'birth_date',
        'education',
        'occupation',
        'address',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'relation' => GuardianRelation::class,
            'birth_date' => 'date',
            'education' => EducationLevel::class,
        ];
    }

    /**
     * Get the user that owns the guardian.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
