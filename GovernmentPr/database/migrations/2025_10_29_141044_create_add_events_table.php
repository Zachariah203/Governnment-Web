<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('add_events', function (Blueprint $table) {
            $table->id('EventID'); // Primary key, auto-increment (matches model)
            $table->unsignedBigInteger('categoryID')->nullable()->index(); // Optional FK
            $table->string('Event', 255); // Maps to form 'title'
            $table->string('UrlName', 255)->nullable(); // Maps to form 'url'
            $table->longText('content')->nullable(); // For Quill rich text
            $table->date('post_date'); // Form 'post_date' (required)
            $table->date('StartDate'); // Form 'StartDate'
            $table->date('EndDate'); // Form 'EndDate'
            $table->text('description', 500)->nullable(); // Form 'description'
            $table->string('category', 255)->nullable(); // Form 'category'
            $table->enum('status', ['draft', 'published', 'archived', 'active', 'inactive'])->default('draft');
            $table->timestamps(); // created_at / updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('add_events');
    }
};