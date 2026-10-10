<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('media_items', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type', ['video', 'audio', 'image'])->default('video');
            $table->string('category')->nullable(); // Sunday Service, Midweek, Excerpt, Higher Life, Special, Event
            $table->string('file_url')->nullable();   // uploaded file path or external URL (YouTube/Vimeo/S3 etc.)
            $table->string('thumbnail_url')->nullable();
            $table->enum('status', ['draft', 'scheduled', 'published', 'archived'])->default('draft');
            $table->timestamp('publish_at')->nullable();
            $table->unsignedBigInteger('views')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_items');
    }
};
