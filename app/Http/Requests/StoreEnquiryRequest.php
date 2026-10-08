<?php

namespace App\Http\Requests;

use App\Models\Enquiry;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEnquiryRequest extends FormRequest
{
    protected $errorBag = 'enquiry';

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(array_keys(Enquiry::TYPES))],
            'name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150', 'required_without:phone'],
            'phone' => ['nullable', 'string', 'max:40', 'required_without:email'],
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
            'guests' => ['nullable', 'integer', 'min:1', 'max:40'],
            'budget' => ['nullable', 'string', 'max:40'],
            'message' => ['nullable', 'string', 'max:2000'],
            'website' => ['nullable', 'max:0'],
        ];
    }

    public function isSpam(): bool
    {
        return filled($this->input('website'));
    }
}
