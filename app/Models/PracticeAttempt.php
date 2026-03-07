<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PracticeAttempt extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'sub_topic_id', 'total_questions', 'correct_answers', 'score', 'completed_at'];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
            'score' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subTopic(): BelongsTo
    {
        return $this->belongsTo(SubTopic::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(PracticeAnswer::class, 'attempt_id');
    }
}
