<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Day 14.5 などの小数日に対応するためにカラム型を変更
     */
    public function up(): void
    {
        Schema::table('mail_templates', function (Blueprint $table) {
            $table->decimal('day_number', 4, 1)->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('mail_templates', function (Blueprint $table) {
            $table->unsignedTinyInteger('day_number')->unique()->change();
        });
    }
};
