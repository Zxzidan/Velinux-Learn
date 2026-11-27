<?php

namespace App\Models;

use App\Enums\LabSessionStatus;
use Database\Factories\LabSessionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'user_id',
    'lab_challenge_id',
    'container_id',
    'status',
    'started_at',
    'expires_at',
    'completed_at',
])]
class LabSession extends Model
{
    /** @use HasFactory<LabSessionFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => LabSessionStatus::class,
            'started_at' => 'datetime',
            'expires_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function labChallenge(): BelongsTo
    {
        return $this->belongsTo(LabChallenge::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }
}
