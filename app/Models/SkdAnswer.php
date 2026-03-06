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
        'question_id',
        'option_id',
        'score',
        'is_doubtful',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(SkdSession::class, 'skd_session_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(Option::class);
    }
}
