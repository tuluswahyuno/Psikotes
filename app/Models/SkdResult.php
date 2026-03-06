<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkdResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'skd_package_id',
        'skd_session_id',
        'twk_score',
        'tiu_score',
        'tkp_score',
        'total_score',
        'twk_passed',
        'tiu_passed',
        'tkp_passed',
        'is_passed',
    ];

    protected $casts = [
        'twk_passed' => 'boolean',
        'tiu_passed' => 'boolean',
        'tkp_passed' => 'boolean',
        'is_passed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(SkdPackage::class, 'skd_package_id');
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(SkdSession::class, 'skd_session_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->is_passed ? 'LULUS' : 'TIDAK LULUS';
    }
}
