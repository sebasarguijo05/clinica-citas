<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('google_token')->nullable()->after('remember_token');
            $table->string('google_refresh_token')->nullable()->after('google_token');
            $table->string('google_calendar_id')->nullable()->after('google_refresh_token');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_token', 'google_refresh_token', 'google_calendar_id']);
        });
    }
};