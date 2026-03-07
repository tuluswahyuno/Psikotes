<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PracticeQuestion extends Model
{
    use HasFactory;

    protected $fillable = ['sub_topic_id', 'question', 'options', 'correct_answer', 'explanation'];

    protected function casts(): array
    {
        return [
            'options' => 'array',
        ];
    }

    public function subTopic(): BelongsTo
    {
        return $this->belongsTo(SubTopic::class);
    }
}
