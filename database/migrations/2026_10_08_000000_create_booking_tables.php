<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('experiences', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->string('category', 40)->index();
            $table->string('scene', 20)->default('reef');
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->json('highlights')->nullable();
            $table->json('includes')->nullable();
            $table->json('excludes')->nullable();
            $table->string('duration', 60)->nullable();
            $table->unsignedTinyInteger('duration_hours')->default(2);
            $table->unsignedInteger('price');            // TTD, "from" price
            $table->string('price_type', 10)->default('person'); // person | group
            $table->unsignedTinyInteger('min_guests')->default(1);
            $table->unsignedTinyInteger('max_guests')->default(10);
            $table->string('child_policy')->nullable();
            $table->string('image_path')->nullable();     // uploaded photo, optional
            $table->boolean('featured')->default(false);
            $table->boolean('active')->default(true);
            $table->unsignedSmallInteger('sort')->default(100);
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 12)->unique();
            $table->string('type', 12)->default('single');   // single | planner
            $table->foreignId('experience_id')->nullable()->constrained()->nullOnDelete();
            $table->json('items')->nullable();                 // planner: [{slug,name,qty,price,price_type}]
            $table->date('date');
            $table->string('time_slot', 20)->nullable();       // morning | afternoon | evening
            $table->unsignedTinyInteger('adults')->default(2);
            $table->unsignedTinyInteger('children')->default(0);
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 40);
            $table->string('contact_via', 12)->default('whatsapp');
            $table->string('pickup', 80)->nullable();
            $table->text('notes')->nullable();
            $table->unsignedInteger('estimate')->default(0);   // TTD
            $table->string('status', 12)->default('new');      // new | confirmed | paid | completed | cancelled
            $table->json('source')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'date']);
        });

        Schema::create('booking_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 20)->default('note');
            $table->text('body');
            $table->timestamps();
        });

        Schema::create('blackout_dates', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique();
            $table->string('reason')->nullable();
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('experience_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('origin', 60)->nullable();
            $table->unsignedTinyInteger('rating')->default(5);
            $table->text('body');
            $table->boolean('approved')->default(false);
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });

        Schema::create('gallery_items', function (Blueprint $table) {
            $table->id();
            $table->string('image_path');
            $table->string('caption')->nullable();
            $table->string('instagram_url')->nullable();
            $table->unsignedSmallInteger('sort')->default(100);
            $table->timestamps();
        });

        Schema::create('enquiries', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 12)->unique();
            $table->string('type', 20);                        // stay | tickets | general
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 40)->nullable();
            $table->date('from')->nullable();
            $table->date('to')->nullable();
            $table->unsignedTinyInteger('guests')->nullable();
            $table->string('budget', 40)->nullable();
            $table->text('message')->nullable();
            $table->string('status', 12)->default('new');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enquiries');
        Schema::dropIfExists('gallery_items');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('blackout_dates');
        Schema::dropIfExists('booking_notes');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('experiences');
    }
};
