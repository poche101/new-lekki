<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notification_topics', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Live Service, New Excerpt, Higher Life, Events, Announcements
            $table->timestamps();
        });

        Schema::create('push_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('endpoint');
            $table->string('public_key')->nullable();
            $table->string('auth_token')->nullable();
            $table->json('topics')->nullable(); // topic names this browser subscribed to; null = all
            $table->timestamps();
        });

        Schema::create('push_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('message');
            $table->foreignId('topic_id')->nullable()->constrained('notification_topics')->nullOnDelete();
            $table->enum('audience', ['all', 'topic', 'members'])->default('all');
            $table->string('url')->nullable(); // destination when tapped
            $table->foreignId('post_id')->nullable()->constrained('posts')->nullOnDelete();
            $table->timestamp('send_at')->nullable(); // null + sent_at set = sent immediately
            $table->timestamp('sent_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('notification_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('push_notification_id')->constrained('push_notifications')->cascadeOnDelete();
            $table->foreignId('push_subscription_id')->nullable()->constrained('push_subscriptions')->nullOnDelete();
            $table->enum('status', ['queued', 'delivered', 'failed', 'opened'])->default('queued');
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_deliveries');
        Schema::dropIfExists('push_notifications');
        Schema::dropIfExists('push_subscriptions');
        Schema::dropIfExists('notification_topics');
    }
};
