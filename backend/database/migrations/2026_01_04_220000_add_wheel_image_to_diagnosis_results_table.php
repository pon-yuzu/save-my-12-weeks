<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('diagnosis_results', function (Blueprint $table) {
            $table->string('wheel_image_path')->nullable()->after('free_text')
                ->comment('ホイール画像のパス（storage/wheel_images/配下）');
        });
    }

    public function down(): void
    {
        Schema::table('diagnosis_results', function (Blueprint $table) {
            $table->dropColumn('wheel_image_path');
        });
    }
};
