<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['slug', 'name', 'category', 'scene', 'tagline', 'description', 'highlights', 'includes', 'excludes', 'duration', 'duration_hours', 'price', 'price_type', 'min_guests', 'max_guests', 'child_policy', 'image_path', 'featured', 'active', 'sort'])]
class Experience extends Model
{
    protected function casts(): array
    {
        return [
            'highlights' => 'array',
            'includes' => 'array',
            'excludes' => 'array',
            'featured' => 'boolean',
            'active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true)->orderBy('sort');
    }

    public function categoryName(): string
    {
        return config("experiences.categories.{$this->category}.name", $this->category);
    }

    public function priceLabel(): string
    {
        return $this->price_type === 'person' ? 'per person' : 'per group';
    }

    /** Estimated TTD total for a party. Children pay half on per-person trips. */
    public function estimate(int $adults, int $children = 0): int
    {
        if ($this->price_type === 'group') {
            return $this->price;
        }

        return (int) round($this->price * $adults + $this->price * 0.5 * $children);
    }

    /** Bundled stock photos live in public/images; admin uploads live on the public storage disk. */
    public function hasBundledImage(): bool
    {
        return is_string($this->image_path) && str_starts_with($this->image_path, 'images/');
    }

    public function imageUrl(): ?string
    {
        if (! $this->image_path) {
            return null;
        }

        return $this->hasBundledImage() ? asset($this->image_path) : asset('storage/'.$this->image_path);
    }
}
