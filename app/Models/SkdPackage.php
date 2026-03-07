<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SkdPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'duration_minutes',
        'twk_question_count',
        'tiu_question_count',
        'tkp_question_count',
        'twk_passing_grade',
        'tiu_passing_grade',
        'tkp_passing_grade',
        'randomize_questions',
        'is_active',
    ];

    protected $casts = [
        'is_active'           => 'boolean',
        'randomize_questions' => 'boolean',
    ];

    public function sessions(): HasMany
    {
        return $this->hasMany(SkdSession::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(SkdResult::class);
    }

    /**
     * Total questions in this package.
     */
    public function getTotalQuestionsAttribute(): int
    {
        return $this->twk_question_count + $this->tiu_question_count + $this->tkp_question_count;
    }

    /**
     * Get the passing grades as array.
     */
    public function getPassingGradesAttribute(): array
    {
        return [
            'twk' => $this->twk_passing_grade,
            'tiu' => $this->tiu_passing_grade,
            'tkp' => $this->tkp_passing_grade,
        ];
    }
}
