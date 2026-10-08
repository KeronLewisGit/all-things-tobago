<?php

namespace App\Http\Requests;

use App\Models\BlackoutDate;
use App\Models\Booking;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    protected $errorBag = 'booking';

    public function rules(): array
    {
        return [
            'date' => ['required', 'date', 'after_or_equal:today', 'before:+1 year', function ($attr, $value, $fail) {
                if (BlackoutDate::whereDate('date', $value)->exists()) {
                    $fail('We are fully booked that day. Pick another date and we will make it work.');
                }
            }],
            'time_slot' => ['nullable', Rule::in(array_keys(Booking::SLOTS))],
            'adults' => ['required', 'integer', 'min:1', 'max:30'],
            'children' => ['nullable', 'integer', 'min:0', 'max:20'],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['required', 'string', 'max:40'],
            'contact_via' => ['nullable', Rule::in(['whatsapp', 'call', 'email'])],
            'pickup' => ['nullable', 'string', 'max:80'],
            'notes' => ['nullable', 'string', 'max:1500'],
            'items' => ['nullable', 'array', 'max:8'],
            'items.*' => ['string', 'exists:experiences,slug'],
            'website' => ['nullable', 'max:0'], // honeypot
        ];
    }

    public function isSpam(): bool
    {
        return filled($this->input('website'));
    }
}
