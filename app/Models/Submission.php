<?php

namespace App\Models;

use Database\Factories\SubmissionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'lab_challenge_id',
    'lab_session_id',
    'submitted_flag',
    'notes',
    'is_correct',
    'score',
    'status',
    'evaluated_by',
    'evaluated_at',
])]
class Submission extends Model
{
    /** @use HasFactory<SubmissionFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
            'score' => 'integer',
            'evaluated_at' => 'datetime',
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

    public function labSession(): BelongsTo
    {
        return $this->belongsTo(LabSession::class);
    }

    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluated_by');
    }
}
