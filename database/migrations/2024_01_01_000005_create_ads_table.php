<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('banner_label')->nullable();
            $table->string('link')->nullable();
            $table->enum('status', ['active', 'paused', 'scheduled'])->default('scheduled');
            $table->date('starts_on')->nullable();
            $table->date('ends_on')->nullable();
            $table->unsignedBigInteger('impressions')->default(0);
            $table->unsignedBigInteger('clicks')->default(0);
            $table->timestamps();
        });

        // Config for the popup behaviour (first delay, interval, rotation) —
        // one row is enough, so it is stored in `settings` (see below), not here.
    }

    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
