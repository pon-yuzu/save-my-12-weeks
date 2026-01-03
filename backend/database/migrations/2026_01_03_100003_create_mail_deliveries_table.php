<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mail_deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained('mail_subscriptions')->onDelete('cascade');
            $table->foreignId('template_id')->constrained('mail_templates')->onDelete('cascade');
            $table->timestamp('sent_at')->nullable();
            $table->enum('status', ['pending', 'sent', 'failed'])->default('pending');
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->index(['subscription_id', 'template_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mail_deliveries');
    }
};
