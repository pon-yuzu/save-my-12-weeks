<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seminar_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seminar_application_id')->constrained()->cascadeOnDelete();
            $table->foreignId('seminar_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('overall_rating')->nullable(); // 1-5
            $table->tinyInteger('content_rating')->nullable(); // 1-5
            $table->text('most_helpful')->nullable(); // 一番役に立ったこと
            $table->text('improvement_suggestions')->nullable(); // 改善点
            $table->boolean('interested_in_program')->default(false); // プログラムに興味あり
            $table->boolean('interested_in_session')->default(false); // 個別セッションに興味あり
            $table->text('questions')->nullable(); // 質問
            $table->string('token', 64)->unique(); // アンケートURL用トークン
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seminar_feedbacks');
    }
};
