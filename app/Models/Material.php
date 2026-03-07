<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Material extends Model
{
    use HasFactory;

    protected $fillable = ['sub_topic_id', 'title', 'content', 'order'];

    public function subTopic(): BelongsTo
    {
        return $this->belongsTo(SubTopic::class);
    }

    public function userProgress(): HasMany
    {
        return $this->hasMany(UserMaterialProgress::class);
    }
}
