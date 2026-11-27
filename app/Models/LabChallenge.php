<?php

namespace App\Models;

use App\Enums\DifficultyLevel;
use Database\Factories\LabChallengeFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'course_id',
    'title',
    'slug',
    'category',
    'difficulty',
    'scenario',
    'instructions',
    'target_host',
    'terminal_prompt',
    'flag',
    'xp_reward',
    'time_limit_minutes',
    'is_active',
])]
class LabChallenge extends Model
{
    /** @use HasFactory<LabChallengeFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'difficulty' => DifficultyLevel::class,
            'xp_reward' => 'integer',
            'time_limit_minutes' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(LabSession::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
