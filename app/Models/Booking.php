<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable(['type', 'experience_id', 'items', 'date', 'time_slot', 'adults', 'children', 'name', 'email', 'phone', 'contact_via', 'pickup', 'notes', 'estimate', 'status', 'source', 'confirmed_at'])]
class Booking extends Model
{
    public const STATUSES = [
        'new' => ['label' => 'New request', 'tone' => 'brand'],
        'confirmed' => ['label' => 'Confirmed', 'tone' => 'sky'],
        'paid' => ['label' => 'Paid', 'tone' => 'emerald'],
        'completed' => ['label' => 'Completed', 'tone' => 'slate'],
        'cancelled' => ['label' => 'Cancelled', 'tone' => 'rose'],
    ];

    public const SLOTS = ['morning' => 'Morning (7 to 11 am)', 'afternoon' => 'Afternoon (12 to 4 pm)', 'evening' => 'Evening (after 5 pm)'];

    protected function casts(): array
    {
        return [
            'items' => 'array',
            'source' => 'array',
            'date' => 'date',
            'confirmed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            if ($booking->reference) {
                return;
            }
            do {
                $reference = 'ATT-'.strtoupper(Str::random(6));
            } while (static::where('reference', $reference)->exists());
            $booking->reference = $reference;
        });
    }

    public function experience(): BelongsTo
    {
        return $this->belongsTo(Experience::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(BookingNote::class)->latest();
    }

    public function scopeOpen(Builder $query): Builder
    {
        return $query->whereIn('status', ['new', 'confirmed', 'paid']);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->open()->whereDate('date', '>=', today())->orderBy('date');
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status]['label'] ?? ucfirst($this->status);
    }

    public function statusTone(): string
    {
        return self::STATUSES[$this->status]['tone'] ?? 'slate';
    }

    public function title(): string
    {
        if ($this->type === 'planner') {
            $count = count($this->items ?? []);

            return $count.' '.Str::plural('experience', $count).' planned';
        }

        return $this->experience?->name ?? 'Experience';
    }

    public function guests(): int
    {
        return $this->adults + $this->children;
    }

    public function slotLabel(): ?string
    {
        return $this->time_slot ? (self::SLOTS[$this->time_slot] ?? ucfirst($this->time_slot)) : null;
    }
}
