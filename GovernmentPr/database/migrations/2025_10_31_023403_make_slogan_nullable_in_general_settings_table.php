<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->string('slogan')->nullable()->change(); // Make it nullable
        });
    }

    public function down(): void
    {
        Schema::table('general_settings', function (Blueprint $table) {
            $table->string('slogan')->nullable(false)->change(); // Revert to NOT NULL
        });
    }
};