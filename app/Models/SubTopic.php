<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubTopic extends Model
{
    use HasFactory;

    protected $fillable = ['section_id', 'title', 'slug', 'description', 'order'];

    public function section(): BelongsTo
    {
        return $this->belongsTo(Section::class);
    }

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class)->orderBy('order');
    }

    public function practiceQuestions(): HasMany
    {
        return $this->hasMany(PracticeQuestion::class);
    }

    public function practiceAttempts(): HasMany
    {
        return $this->hasMany(PracticeAttempt::class);
    }
}
