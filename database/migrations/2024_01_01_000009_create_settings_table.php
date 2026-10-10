<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Simple key/value store for site-wide settings: church details, the
        // Espees merchant code, ad popup timing, homepage section order/visibility,
        // and the three console capsule configs. Read/written as JSON via the
        // Setting model helper (Setting::get('home_sections'), etc.).
        Schema::create('settings', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->json('value')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
