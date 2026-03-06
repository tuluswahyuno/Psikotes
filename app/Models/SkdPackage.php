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
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function packageTests(): HasMany
    {
        return $this->hasMany(SkdPackageTest::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(SkdSession::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(SkdResult::class);
    }

    public function getTestByType(string $type)
    {
        return $this->packageTests()->where('sub_test_type', $type)->first();
    }

    public function getTotalQuestionsAttribute(): int
    {
        $total = 0;
        foreach ($this->packageTests as $pt) {
            $total += $pt->test->questions()->count();
        }
        return $total;
    }
}
