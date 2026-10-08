<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['experience_id', 'name', 'origin', 'rating', 'body', 'approved', 'featured'])]
class Review extends Model
{
    protected function casts(): array
    {
        return ['approved' => 'boolean', 'featured' => 'boolean'];
    }

    public function experience(): BelongsTo
    {
        return $this->belongsTo(Experience::class);
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('approved', true)->latest();
    }
}
