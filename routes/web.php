<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EnquiryController as AdminEnquiryController;
use App\Http\Controllers\Admin\ExperienceController as AdminExperienceController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EnquiryController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PlannerController;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

/*
| Public site.
*/
Route::get('/', [PageController::class, 'home'])->name('home');
Route::controller(PageController::class)->group(function () {
    Route::get('/about', 'about')->name('about');
    Route::get('/faq', 'faq')->name('faq');
    Route::get('/contact', 'contact')->name('contact');
    Route::get('/stay', 'stay')->name('stay');
    Route::get('/events', 'events')->name('events');
    Route::get('/links', 'links')->name('links');
    Route::get('/privacy', 'privacy')->name('privacy');
    Route::get('/terms', 'terms')->name('terms');
    Route::get('/photo-credits', 'photoCredits')->name('photo-credits');
});

Route::get('/experiences', [ExperienceController::class, 'index'])->name('experiences');
Route::get('/experiences/{experience}', [ExperienceController::class, 'show'])->name('experiences.show');
Route::post('/experiences/{experience}/book', [BookingController::class, 'store'])->middleware('throttle:10,1')->name('bookings.store');

Route::get('/plan-your-day', [PlannerController::class, 'index'])->name('planner');
Route::post('/plan-your-day', [BookingController::class, 'planner'])->middleware('throttle:10,1')->name('planner.store');

Route::post('/enquiries', [EnquiryController::class, 'store'])->middleware('throttle:10,1')->name('enquiries.store');

Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('robots');

/*
| Booking dashboard (admin).
*/
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/admin/login', [AuthController::class, 'login'])->middleware('throttle:10,1')->name('login.attempt');

Route::middleware('auth')->prefix('admin')->name('admin.')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings');
    Route::get('/bookings/export', [AdminBookingController::class, 'export'])->name('bookings.export');
    Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
    Route::patch('/bookings/{booking}', [AdminBookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [AdminBookingController::class, 'destroy'])->name('bookings.destroy');

    Route::get('/calendar', [CalendarController::class, 'index'])->name('calendar');
    Route::post('/calendar/blackouts', [CalendarController::class, 'storeBlackout'])->name('blackouts.store');
    Route::delete('/calendar/blackouts/{blackout}', [CalendarController::class, 'destroyBlackout'])->name('blackouts.destroy');

    Route::get('/experiences', [AdminExperienceController::class, 'index'])->name('experiences');
    Route::get('/experiences/{experience}', [AdminExperienceController::class, 'edit'])->name('experiences.edit');
    Route::patch('/experiences/{experience}', [AdminExperienceController::class, 'update'])->name('experiences.update');

    Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::patch('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery');
    Route::post('/gallery', [GalleryController::class, 'store'])->name('gallery.store');
    Route::delete('/gallery/{item}', [GalleryController::class, 'destroy'])->name('gallery.destroy');

    Route::get('/enquiries', [AdminEnquiryController::class, 'index'])->name('enquiries');
    Route::patch('/enquiries/{enquiry}', [AdminEnquiryController::class, 'update'])->name('enquiries.update');
});
