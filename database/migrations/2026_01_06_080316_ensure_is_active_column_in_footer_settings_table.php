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
        if (Schema::hasTable('footer_settings')) {
            Schema::table('footer_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('footer_settings', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('copyright_sv');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('footer_settings', function (Blueprint $table) {
            if (Schema::hasColumn('footer_settings', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
