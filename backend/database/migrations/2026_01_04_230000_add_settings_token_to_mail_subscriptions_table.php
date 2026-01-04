<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mail_subscriptions', function (Blueprint $table) {
            $table->string('settings_token', 64)->nullable()->unique()->after('token')
                ->comment('配信時間設定ページ用トークン');
        });
    }

    public function down(): void
    {
        Schema::table('mail_subscriptions', function (Blueprint $table) {
            $table->dropColumn('settings_token');
        });
    }
};
