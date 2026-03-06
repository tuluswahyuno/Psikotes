<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SkdSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skd_package_id',
        'status',
        'started_at',
        'finished_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(SkdPackage::class, 'skd_package_id');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(SkdAnswer::class);
    }

    public function result(): HasOne
    {
        return $this->hasOne(SkdResult::class);
    }

    public function getRemainingTimeAttribute(): int
    {
        if (!$this->started_at) {
            return $this->package->duration_minutes * 60;
        }
        $endTime = $this->started_at->addMinutes($this->package->duration_minutes);
        $remainingSeconds = now()->diffInSeconds($endTime, false);
        return max(0, $remainingSeconds);
    }

    public function isExpired(): bool
    {
        return $this->remaining_time <= 0;
    }
}
