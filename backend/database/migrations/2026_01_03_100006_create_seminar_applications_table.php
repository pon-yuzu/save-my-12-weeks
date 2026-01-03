<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seminar_applications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->enum('age_group', ['20s', '30s', '40s', '50s_plus', 'prefer_not']);
            $table->string('occupation');
            $table->string('occupation_other')->nullable();
            $table->string('referral_source');
            $table->string('referral_other')->nullable();
            $table->enum('has_canceled_plans', ['yes', 'no', 'dont_remember']);
            $table->text('cancel_reason')->nullable();
            $table->text('twelve_weeks_dream');
            $table->text('questions')->nullable();
            $table->timestamps();

            $table->index('email');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seminar_applications');
    }
};
