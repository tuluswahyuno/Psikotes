<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PracticeAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['attempt_id', 'practice_question_id', 'user_answer', 'is_correct'];

    protected function casts(): array
    {
        return [
            'is_correct' => 'boolean',
        ];
    }

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(PracticeAttempt::class, 'attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(PracticeQuestion::class, 'practice_question_id');
    }
}
