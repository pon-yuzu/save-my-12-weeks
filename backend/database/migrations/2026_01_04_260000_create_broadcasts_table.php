<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('broadcasts', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('body');
            $table->enum('target_type', ['all', 'individual', 'filtered'])->default('all');
            $table->json('target_filter')->nullable()->comment('フィルター条件（day_min, day_max など）');
            $table->json('recipient_ids')->nullable()->comment('個別送信時の宛先ID');
            $table->enum('status', ['draft', 'scheduled', 'sending', 'sent'])->default('draft');
            $table->timestamp('scheduled_at')->nullable()->comment('予約配信日時');
            $table->timestamp('sent_at')->nullable();
            $table->unsignedInteger('sent_count')->default(0);
            $table->unsignedInteger('opened_count')->default(0);
            $table->timestamps();
        });

        // 配信履歴（誰に送ったか）
        Schema::create('broadcast_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('broadcast_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->constrained('mail_subscriptions')->onDelete('cascade');
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->string('error_message')->nullable();
            $table->timestamps();

            $table->unique(['broadcast_id', 'subscription_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('broadcast_recipients');
        Schema::dropIfExists('broadcasts');
    }
};
