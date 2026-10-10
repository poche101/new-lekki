<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Real, first-party analytics: one row per page view, written by the
        // TrackPageView middleware. This is what the admin analytics page reads
        // from — no external analytics service is wired up yet (see README).
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('session_id', 64)->nullable();
            $table->string('device_type', 20)->nullable(); // mobile, desktop, tablet
            $table->string('referrer')->nullable();
            $table->timestamp('viewed_at')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
