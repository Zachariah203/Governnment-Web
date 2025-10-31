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
        // Conditionally create media_categories table if it doesn't exist
        if (!Schema::hasTable('media_categories')) {
            Schema::create('media_categories', function (Blueprint $table) {
                $table->id('category_id');  // Primary key
                $table->string('name')->unique();   // Category name
                $table->text('icon')->nullable();   // Category icon
                $table->timestamps();
            });
        }

        // Add category_id to existing media table (alter, not create)
        if (Schema::hasTable('media') && !Schema::hasColumn('media', 'category_id')) {
            Schema::table('media', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id')->nullable()->after('size');
                $table->foreign('category_id')->references('category_id')->on('media_categories')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the foreign key and column if they exist
        if (Schema::hasTable('media') && Schema::hasColumn('media', 'category_id')) {
            Schema::table('media', function (Blueprint $table) {
                $table->dropForeign(['category_id']);
                $table->dropColumn('category_id');
            });
        }

        // Drop media_categories table if it was created by this migration
        if (Schema::hasTable('media_categories')) {
            Schema::dropIfExists('media_categories');
        }
    }
};