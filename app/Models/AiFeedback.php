<?php

namespace App\Models;

use Database\Factories\AiFeedbackFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Table(name: 'ai_feedback')]
#[Fillable([
    'assessment_id',
    'answer_id',
    'recommended_course_id',
    'summary',
    'strengths',
    'improvements',
    'recommended_topic',
    'confidence_score',
    'model_name',
    'mentor_notes',
])]
class AiFeedback extends Model
{
    /** @use HasFactory<AiFeedbackFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'confidence_score' => 'decimal:2',
        ];
    }

    public function assessment(): BelongsTo
    {
        return $this->belongsTo(Assessment::class);
    }

    public function answer(): BelongsTo
    {
        return $this->belongsTo(Answer::class);
    }

    public function recommendedCourse(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'recommended_course_id');
    }
}
