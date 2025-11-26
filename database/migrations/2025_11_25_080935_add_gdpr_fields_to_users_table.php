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
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('gdpr_consent_at')->nullable()->after('email_verified_at');
            $table->timestamp('gdpr_reminder_sent_at')->nullable()->after('gdpr_consent_at');
            $table->boolean('newsletter_subscribed')->default(false)->after('gdpr_reminder_sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['gdpr_consent_at', 'gdpr_reminder_sent_at', 'newsletter_subscribed']);
        });
    }
};
