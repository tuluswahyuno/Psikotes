<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'description',
        'duration_minutes',
        'type', // e.g., 'multiple_choice', 'essay'
        'randomize_questions',
        'is_active',
        'passing_score',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'randomize_questions' => 'boolean',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
}
