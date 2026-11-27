<?php

namespace App\Models;

use App\Enums\PointSourceType;
use Database\Factories\UserPointFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'source_type',
    'source_id',
    'points',
    'description',
    'awarded_at',
])]
class UserPoint extends Model
{
    /** @use HasFactory<UserPointFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'source_type' => PointSourceType::class,
            'points' => 'integer',
            'awarded_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
