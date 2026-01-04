<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('seminar_applications', function (Blueprint $table) {
            $table->foreignId('seminar_id')->nullable()->after('id')->constrained()->nullOnDelete();
            $table->datetime('reminder_1day_sent_at')->nullable();
            $table->datetime('reminder_1hour_sent_at')->nullable();
            $table->datetime('followup_sent_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('seminar_applications', function (Blueprint $table) {
            $table->dropForeign(['seminar_id']);
            $table->dropColumn(['seminar_id', 'reminder_1day_sent_at', 'reminder_1hour_sent_at', 'followup_sent_at']);
        });
    }
};
