<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class EnquiryController extends Controller
{
    public function index(): View
    {
        return view('admin.enquiries', ['enquiries' => Enquiry::latest()->paginate(30)]);
    }

    public function update(Request $request, Enquiry $enquiry): RedirectResponse
    {
        $enquiry->update($request->validate(['status' => ['required', Rule::in(['new', 'replied', 'booked', 'closed'])]]));

        return back()->with('saved', true);
    }
}
