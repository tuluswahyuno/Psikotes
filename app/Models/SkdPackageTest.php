<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SkdPackageTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'skd_package_id',
        'test_id',
        'sub_test_type',
        'passing_grade',
        'score_per_correct',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(SkdPackage::class, 'skd_package_id');
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function getSubTestLabelAttribute(): string
    {
        return match ($this->sub_test_type) {
            'twk' => 'Tes Wawasan Kebangsaan (TWK)',
            'tiu' => 'Tes Intelegensi Umum (TIU)',
            'tkp' => 'Tes Karakteristik Pribadi (TKP)',
            default => strtoupper($this->sub_test_type),
        };
    }
}
