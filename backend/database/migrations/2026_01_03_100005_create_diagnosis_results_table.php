<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('diagnosis_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->nullable()->constrained('mail_subscriptions')->onDelete('set null');
            $table->unsignedTinyInteger('health_score')->comment('1-10');
            $table->unsignedTinyInteger('mind_score')->comment('1-10');
            $table->unsignedTinyInteger('money_score')->comment('1-10');
            $table->unsignedTinyInteger('career_score')->comment('1-10');
            $table->unsignedTinyInteger('time_score')->comment('1-10');
            $table->unsignedTinyInteger('living_score')->comment('1-10');
            $table->unsignedTinyInteger('relationships_score')->comment('1-10');
            $table->unsignedTinyInteger('vision_score')->comment('1-10');
            $table->json('selected_areas')->nullable()->comment('改善したい領域');
            $table->text('free_text')->nullable();
            $table->timestamps();

            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('diagnosis_results');
    }
};
