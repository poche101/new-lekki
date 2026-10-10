<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // One table, many content types — matches how the SRD describes the blog:
        // "whatever is posted on the blog shows on the homepage". Excerpts, Best
        // Dresser entries, Events and Announcements are all rows here, filtered
        // by `type`. Higher Life episodes and static Pages live here too.
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['article', 'excerpt', 'event', 'best_dresser', 'announcement', 'higher_life', 'page', 'testimony'])->index();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('excerpt_text')->nullable(); // short summary/blurb shown in listings
            $table->longText('body')->nullable();
            $table->foreignId('media_item_id')->nullable()->constrained('media_items')->nullOnDelete();
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('status', ['draft', 'scheduled', 'published', 'archived'])->default('draft')->index();
            $table->timestamp('published_at')->nullable()->index();

            // type-specific fields, kept flexible rather than one column per type
            $table->json('meta')->nullable();
            // event: { "starts_at": "...", "location": "...", "reminder_enabled": true }
            // higher_life: { "episode": 142, "season": 9, "speaker": "...", "duration_mins": 34 }
            // best_dresser: { "name": "...", "month": "September", "featured": true }
            // announcement/article: { "read_minutes": 4 }

            $table->boolean('comments_enabled')->default(true);
            $table->boolean('notify_on_publish')->default(false);
            $table->unsignedBigInteger('views')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
