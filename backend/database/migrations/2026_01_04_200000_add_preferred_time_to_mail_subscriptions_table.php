<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mail_subscriptions', function (Blueprint $table) {
            $table->time('preferred_time')->default('07:00')->after('current_day')
                ->comment('希望配信時間（デフォルト: 07:00）');
        });
    }

    public function down(): void
    {
        Schema::table('mail_subscriptions', function (Blueprint $table) {
            $table->dropColumn('preferred_time');
        });
    }
};
