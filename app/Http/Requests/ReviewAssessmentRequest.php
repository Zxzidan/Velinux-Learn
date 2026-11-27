<?php

namespace App\Http\Requests;

use App\Enums\AssessmentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ReviewAssessmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'score' => ['required', 'integer', 'min:0', 'max:100'],
            'status' => ['required', Rule::enum(AssessmentStatus::class)],
            'mentor_notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
