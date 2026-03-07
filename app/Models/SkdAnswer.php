<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkdAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'skd_session_id',
        'practice_question_id',
        'answer',      // A, B, C, D, or E
        'is_doubtful',
        'score',
    ];

    protected $casts = [
        'is_doubtful' => 'boolean',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(SkdSession::class, 'skd_session_id');
    }

    public function practiceQuestion(): BelongsTo
    {
        return $this->belongsTo(PracticeQuestion::class, 'practice_question_id');
    }
}
