<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable(['type', 'name', 'email', 'phone', 'from', 'to', 'guests', 'budget', 'message', 'status'])]
class Enquiry extends Model
{
    public const TYPES = ['stay' => 'Accommodation', 'tickets' => 'Event tickets', 'general' => 'General'];

    protected function casts(): array
    {
        return ['from' => 'date', 'to' => 'date'];
    }

    protected static function booted(): void
    {
        static::creating(function (Enquiry $enquiry) {
            $enquiry->reference ??= 'ENQ-'.strtoupper(Str::random(6));
        });
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? ucfirst($this->type);
    }
}
