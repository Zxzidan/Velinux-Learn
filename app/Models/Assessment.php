<?php

namespace App\Models;

use App\Enums\AssessmentStatus;
use Database\Factories\AssessmentFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'user_id',
    'quiz_id',
    'score',
    'max_score',
    'status',
    'ai_evaluated_at',
    'reviewed_by',
    'reviewed_at',
    'started_at',
    'completed_at',
])]
class Assessment extends Model
{
    /** @use HasFactory<AssessmentFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'max_score' => 'integer',
            'status' => AssessmentStatus::class,
            'ai_evaluated_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function aiFeedback(): HasOne
    {
        return $this->hasOne(AiFeedback::class);
    }

    #[Scope]
    protected function needsReview(Builder $query): Builder
    {
        return $query->where('status', AssessmentStatus::NeedsReview);
    }
}
