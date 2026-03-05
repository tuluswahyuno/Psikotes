<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'test_id',
        'test_session_id',
        'total_score',
        'score_percentage',
        'interpretation',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(TestSession::class, 'test_session_id');
    }
}
