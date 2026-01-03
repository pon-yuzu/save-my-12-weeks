<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('unsubscribe_reasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained('mail_subscriptions')->onDelete('cascade');
            $table->text('reason');
            $table->timestamp('unsubscribed_at');
            $table->timestamps();

            $table->index('unsubscribed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('unsubscribe_reasons');
    }
};
