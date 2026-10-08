<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEnquiryRequest;
use App\Models\Enquiry;
use App\Models\User;
use App\Notifications\NewEnquiryNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;

class EnquiryController extends Controller
{
    public function store(StoreEnquiryRequest $request): RedirectResponse
    {
        if ($request->isSpam()) {
            return back()->with('enquiry_success', ['reference' => null]);
        }

        $enquiry = Enquiry::create($request->validated());

        if ($recipient = User::first()) {
            $recipient->notify(new NewEnquiryNotification($enquiry));
        }

        return back()->with('enquiry_success', ['reference' => $enquiry->reference, 'name' => Str::before($enquiry->name, ' '), 'type' => $enquiry->type]);
    }
}
