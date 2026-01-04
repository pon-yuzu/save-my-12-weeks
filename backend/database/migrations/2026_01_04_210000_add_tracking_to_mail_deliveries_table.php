<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mail_deliveries', function (Blueprint $table) {
            $table->string('tracking_token', 64)->nullable()->unique()->after('error_message')
                ->comment('開封トラッキング用のユニークトークン');
            $table->timestamp('opened_at')->nullable()->after('tracking_token')
                ->comment('開封日時');
            $table->unsignedInteger('open_count')->default(0)->after('opened_at')
                ->comment('開封回数');

            $table->index('tracking_token');
            $table->index('opened_at');
        });
    }

    public function down(): void
    {
        Schema::table('mail_deliveries', function (Blueprint $table) {
            $table->dropIndex(['tracking_token']);
            $table->dropIndex(['opened_at']);
            $table->dropColumn(['tracking_token', 'opened_at', 'open_count']);
        });
    }
};
